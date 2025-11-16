<?php

namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InsuranceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        $claims = InsuranceClaim::with(['patient', 'visit'])
            ->when($status, fn ($query) => $query->where('claim_status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('policy_number', 'like', "%{$search}%")
                        ->orWhere('provider', 'like', "%{$search}%")
                        ->orWhereHas('patient', function ($patientQuery) use ($search) {
                            $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('hospital_id', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $patients = Patient::select('id', 'first_name', 'last_name', 'hospital_id')->orderBy('first_name')->limit(100)->get();

        $statusCounts = InsuranceClaim::select('claim_status', DB::raw('count(*) as total'))
            ->groupBy('claim_status')
            ->pluck('total', 'claim_status');

        return view('insurance.index', compact('claims', 'patients', 'statusCounts', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'policy_number' => ['required', 'string', 'max:120'],
            'provider' => ['required', 'string', 'max:120'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'co_pay_amount' => ['nullable', 'numeric', 'min:0'],
            'documents' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ]);

        $documents = $this->decodeDocuments($data['documents'] ?? null);

        $claim = InsuranceClaim::create([
            ...$data,
            'documents' => $documents,
            'submitted_at' => now(),
        ]);

        return redirect()->route('insurance.claims.show', $claim)->with('status', 'Claim submitted successfully.');
    }

    public function show(InsuranceClaim $claim)
    {
        $claim->load(['patient.wallet', 'visit']);

        return view('insurance.show', compact('claim'));
    }

    public function update(Request $request, InsuranceClaim $claim)
    {
        $data = $request->validate([
            'claim_status' => ['required', Rule::in(['draft', 'submitted', 'approved', 'rejected', 'paid'])],
            'approved_amount' => ['nullable', 'numeric', 'min:0'],
            'co_pay_amount' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'documents' => ['nullable', 'string'],
        ]);

        $documents = $this->decodeDocuments($data['documents'] ?? null, $claim->documents);
        $approvedAmount = array_key_exists('approved_amount', $data) && $data['approved_amount'] !== null
            ? (float) $data['approved_amount']
            : (float) $claim->approved_amount;
        $coPayAmount = array_key_exists('co_pay_amount', $data) && $data['co_pay_amount'] !== null
            ? (float) $data['co_pay_amount']
            : (float) $claim->co_pay_amount;

        $shouldDeductCoPay = in_array($data['claim_status'], ['approved', 'paid'], true)
            && $coPayAmount > 0
            && is_null($claim->co_pay_deducted_at);

        $wallet = optional($claim->patient)->wallet;

        if ($shouldDeductCoPay && !$wallet) {
            return back()->withErrors([
                'co_pay_amount' => 'Unable to deduct co-pay because wallet is missing.',
            ])->withInput();
        }

        if ($shouldDeductCoPay && $wallet->balance < $coPayAmount) {
            return back()->withErrors([
                'co_pay_amount' => 'Insufficient wallet balance for co-pay.',
            ])->withInput();
        }

        DB::transaction(function () use ($claim, $data, $documents, $approvedAmount, $coPayAmount, $shouldDeductCoPay, $wallet) {
            $updates = [
                'claim_status' => $data['claim_status'],
                'approved_amount' => $approvedAmount,
                'co_pay_amount' => $coPayAmount,
            ];

            if ($documents !== $claim->documents) {
                $updates['documents'] = $documents;
            }

            if (array_key_exists('remarks', $data)) {
                $updates['remarks'] = $data['remarks'];
            }

            if ($data['claim_status'] === 'submitted' && is_null($claim->submitted_at)) {
                $updates['submitted_at'] = now();
            }

            if (in_array($data['claim_status'], ['approved', 'rejected', 'paid']) && is_null($claim->responded_at)) {
                $updates['responded_at'] = now();
            }

            if ($shouldDeductCoPay && $wallet) {
                $wallet->refresh();
                $newBalance = $wallet->balance - $coPayAmount;
                $wallet->update(['balance' => $newBalance]);

                $wallet->transactions()->create([
                    'transaction_type' => 'deduction',
                    'payment_method' => 'wallet',
                    'amount' => $coPayAmount,
                    'balance_after' => $newBalance,
                    'service' => 'NHIS Co-Pay',
                    'description' => 'Co-pay deduction for claim #' . $claim->id,
                    'performed_by' => 'Insurance Desk',
                    'transacted_at' => now(),
                ]);

                $updates['co_pay_deducted_at'] = now();
            }

            $claim->update($updates);
        });

        return redirect()->route('insurance.claims.show', $claim)->with('status', 'Claim updated successfully.');
    }

    protected function decodeDocuments(?string $text, ?array $fallback = null): ?array
    {
        if (is_null($text)) {
            return $fallback;
        }

        $decoded = json_decode($text, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'documents' => 'Documents payload must be valid JSON.',
            ]);
        }

        return $decoded;
    }
}
