<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Surgery;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TheatreController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        $surgeries = Surgery::with(['patient', 'visit'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('procedure_name', 'like', "%{$search}%")
                        ->orWhereHas('patient', function ($patientQuery) use ($search) {
                            $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('hospital_id', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('scheduled_at')
            ->paginate(10)
            ->withQueryString();

        $openVisits = Visit::with('patient')
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest('scheduled_at')
            ->limit(20)
            ->get();

        return view('theatre.index', compact('surgeries', 'status', 'search', 'openVisits'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'procedure_name' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['nullable', 'string', 'max:120'],
            'scheduled_at' => ['nullable', 'date'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'materials_used' => ['nullable', 'string'],
        ]);

        $materials = $this->formatMaterials($data['materials_used'] ?? null);

        $surgery = Surgery::create([
            ...$data,
            'materials_used' => $materials,
        ]);

        return redirect()->route('theatre.surgeries.show', $surgery)->with('status', 'Surgery scheduled successfully.');
    }

    public function show(Surgery $surgery)
    {
        $surgery->load(['patient.wallet', 'visit']);

        return view('theatre.show', compact('surgery'));
    }

    public function update(Request $request, Surgery $surgery)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['scheduled', 'in_progress', 'completed', 'billed'])],
            'surgeon_name' => ['nullable', 'string', 'max:120'],
            'actual_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'materials_used' => ['nullable', 'string'],
        ]);

        $actualCost = array_key_exists('actual_cost', $data) && $data['actual_cost'] !== null
            ? (float) $data['actual_cost']
            : (float) $surgery->actual_cost;

        $shouldBill = $data['status'] === 'completed' && is_null($surgery->completed_at) && $actualCost > 0;
        $wallet = optional($surgery->patient)->wallet;

        if ($shouldBill && !$wallet) {
            return back()->withErrors([
                'actual_cost' => 'Wallet not found for patient to deduct surgery cost.',
            ])->withInput();
        }

        if ($shouldBill && $wallet?->balance < $actualCost) {
            return back()->withErrors([
                'actual_cost' => 'Insufficient wallet funds for surgery billing.',
            ])->withInput();
        }

        $materials = $this->formatMaterials($data['materials_used'] ?? null, $surgery->materials_used);

        DB::transaction(function () use ($surgery, $data, $actualCost, $shouldBill, $wallet, $materials) {
            $updates = [
                'status' => $data['status'],
                'surgeon_name' => $data['surgeon_name'] ?? $surgery->surgeon_name,
                'actual_cost' => $actualCost,
                'materials_used' => $materials,
            ];

            if (array_key_exists('notes', $data)) {
                $updates['notes'] = $data['notes'];
            }

            if ($data['status'] === 'in_progress' && is_null($surgery->started_at)) {
                $updates['started_at'] = now();
            }

            if ($data['status'] === 'completed' && is_null($surgery->completed_at)) {
                $updates['completed_at'] = now();
            }

            if ($shouldBill && $wallet) {
                $wallet->refresh();
                $newBalance = $wallet->balance - $actualCost;
                $wallet->update(['balance' => $newBalance]);

                $wallet->transactions()->create([
                    'transaction_type' => 'deduction',
                    'payment_method' => 'wallet',
                    'amount' => $actualCost,
                    'balance_after' => $newBalance,
                    'service' => 'Theatre - ' . $surgery->procedure_name,
                    'description' => 'Surgical charges posted from theatre module.',
                    'performed_by' => $data['surgeon_name'] ?? 'Theatre Desk',
                    'transacted_at' => now(),
                ]);
            }

            $surgery->update($updates);
        });

        return redirect()->route('theatre.surgeries.show', $surgery)->with('status', 'Surgery updated successfully.');
    }

    protected function formatMaterials(?string $rawMaterials, ?array $fallback = null): ?array
    {
        if (is_null($rawMaterials)) {
            return $fallback;
        }

        $lines = array_filter(array_map('trim', preg_split('/\r?\n/', $rawMaterials)));

        return empty($lines) ? null : array_values($lines);
    }
}
