<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        $labTests = LabTest::with(['visit.patient'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('test_name', 'like', "%{$search}%")
                        ->orWhereHas('visit.patient', function ($patientQuery) use ($search) {
                            $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('hospital_id', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statusCounts = LabTest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('laboratory.index', compact('labTests', 'status', 'search', 'statusCounts'));
    }

    public function show(LabTest $labTest)
    {
        $labTest->load(['visit.patient.wallet']);

        return view('laboratory.show', compact('labTest'));
    }

    public function update(Request $request, LabTest $labTest)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'technician_name' => ['nullable', 'string', 'max:120'],
            'result_summary' => ['nullable', 'string'],
            'result_payload' => ['nullable', 'string'],
            'charge_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $labTest->loadMissing('visit.patient.wallet');
        $status = $data['status'];
        $chargeAmount = array_key_exists('charge_amount', $data) && $data['charge_amount'] !== null
            ? (float) $data['charge_amount']
            : (float) $labTest->charge_amount;

        $resultData = null;

        if (!empty($data['result_payload'])) {
            $decoded = json_decode($data['result_payload'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors([
                    'result_payload' => 'Result payload must be valid JSON.',
                ])->withInput();
            }
            $resultData = $decoded;
        }

        $shouldDeduct = $status === 'completed' && is_null($labTest->charged_at) && $chargeAmount > 0;
        $wallet = optional($labTest->visit?->patient)->wallet;

        if ($shouldDeduct && !$wallet) {
            return back()->withErrors([
                'charge_amount' => 'Patient wallet not found for deduction.',
            ])->withInput();
        }

        if ($shouldDeduct && $wallet->balance < $chargeAmount) {
            return back()->withErrors([
                'charge_amount' => 'Insufficient wallet funds to complete this test.',
            ])->withInput();
        }

        DB::transaction(function () use ($labTest, $status, $data, $chargeAmount, $resultData, $shouldDeduct, $wallet) {
            $updateData = [
                'status' => $status,
                'technician_name' => $data['technician_name'] ?? $labTest->technician_name,
                'result_summary' => $data['result_summary'] ?? $labTest->result_summary,
                'charge_amount' => $chargeAmount,
            ];

            if (!is_null($resultData)) {
                $updateData['result_data'] = $resultData;
            }

            if ($status === 'completed') {
                $updateData['result_at'] = now();
            }

            if ($shouldDeduct && $wallet) {
                $wallet->refresh();
                $newBalance = $wallet->balance - $chargeAmount;
                $wallet->update(['balance' => $newBalance]);

                $wallet->transactions()->create([
                    'transaction_type' => 'deduction',
                    'payment_method' => 'wallet',
                    'amount' => $chargeAmount,
                    'balance_after' => $newBalance,
                    'service' => 'Laboratory - ' . $labTest->test_name,
                    'description' => 'Automatic charge for lab test.',
                    'performed_by' => $data['technician_name'] ?? 'Laboratory Team',
                    'transacted_at' => now(),
                ]);

                $updateData['charged_at'] = now();
            }

            $labTest->update($updateData);
        });

        return redirect()->route('laboratory.tests.show', $labTest)->with('status', 'Lab test updated successfully.');
    }
}
