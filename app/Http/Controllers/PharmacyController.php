<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        $prescriptions = Prescription::with(['visit.patient'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('drug_name', 'like', "%{$search}%")
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

        $statusCounts = Prescription::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('pharmacy.index', compact('prescriptions', 'status', 'search', 'statusCounts'));
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['visit.patient.wallet']);

        return view('pharmacy.show', compact('prescription'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'dispensed', 'rejected'])],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'charge_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'dispensed_by' => ['nullable', 'string', 'max:120'],
        ]);

        $prescription->loadMissing('visit.patient.wallet');
        $status = $data['status'];
        $unitPrice = array_key_exists('unit_price', $data)
            ? (float) $data['unit_price']
            : (float) $prescription->unit_price;
        $quantity = array_key_exists('quantity', $data)
            ? (int) $data['quantity']
            : ($prescription->quantity ?: 1);
        $chargeAmount = array_key_exists('charge_amount', $data) && $data['charge_amount'] !== null
            ? (float) $data['charge_amount']
            : ($unitPrice * max($quantity, 1));

        $shouldDeduct = $status === 'dispensed' && is_null($prescription->dispensed_at);

        if ($shouldDeduct && $chargeAmount <= 0) {
            return back()->withErrors([
                'charge_amount' => 'Please capture the amount to deduct from wallet.',
            ])->withInput();
        }

        $wallet = optional($prescription->visit?->patient)->wallet;

        if ($shouldDeduct && !$wallet) {
            return back()->withErrors([
                'status' => 'Patient wallet not found. Please configure wallet before dispensing.',
            ])->withInput();
        }

        if ($shouldDeduct && $wallet->balance < $chargeAmount) {
            return back()->withErrors([
                'charge_amount' => 'Insufficient wallet balance to dispense this prescription.',
            ])->withInput();
        }

        DB::transaction(function () use ($prescription, $status, $unitPrice, $quantity, $chargeAmount, $data, $shouldDeduct, $wallet) {
            $updateData = [
                'status' => $status,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'total_cost' => $chargeAmount,
            ];

            if (array_key_exists('notes', $data)) {
                $updateData['notes'] = $data['notes'];
            }

            if ($status === 'dispensed') {
                $updateData['dispensed_at'] = now();
                $updateData['dispensed_by'] = $data['dispensed_by'] ?? 'Pharmacy Team';
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
                    'service' => 'Pharmacy - ' . $prescription->drug_name,
                    'description' => 'Dispensed medication from pharmacy module.',
                    'performed_by' => $data['dispensed_by'] ?? 'Pharmacy Team',
                    'transacted_at' => now(),
                ]);
            }

            $prescription->update($updateData);
        });

        return redirect()->route('pharmacy.prescriptions.show', $prescription)->with('status', 'Prescription updated successfully.');
    }
}
