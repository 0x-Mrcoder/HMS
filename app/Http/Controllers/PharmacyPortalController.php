<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyPortalController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->role === 'pharmacy', 403);

        $pendingQueue = Prescription::with(['visit.patient'])
            ->whereIn('status', ['pending'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentDispensed = Prescription::with(['visit.patient'])
            ->where('status', 'dispensed')
            ->latest('dispensed_at')
            ->limit(5)
            ->get();

        $visitsAwaitingPharmacy = Visit::with(['patient', 'department'])
            ->whereHas('prescriptions', fn ($query) => $query->where('status', 'pending'))
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $metrics = [
            'pending_orders' => $pendingQueue->count(),
            'dispensed_today' => Prescription::where('status', 'dispensed')
                ->whereDate('dispensed_at', today())
                ->count(),
            'wallet_deductions_today' => Prescription::where('status', 'dispensed')
                ->whereDate('dispensed_at', today())
                ->sum('total_cost'),
        ];

        return view('pharmacy.dashboard', compact(
            'metrics',
            'pendingQueue',
            'recentDispensed',
            'visitsAwaitingPharmacy'
        ));
    }
    public function index()
    {
        $prescriptions = Prescription::with(['visit.patient'])
            ->latest()
            ->paginate(20);

        return view('pharmacy.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['visit.patient.wallet']);
        
        // Check current stock
        $drug = \App\Models\Drug::where('name', $prescription->drug_name)->first();
        $stock = $drug ? $drug->stock : 0;
        $price = $drug ? $drug->price : 0;

        return view('pharmacy.prescriptions.show', compact('prescription', 'stock', 'price'));
    }

    public function dispense(Request $request, Prescription $prescription)
    {
        if ($prescription->status === 'dispensed') {
            return back()->with('error', 'Prescription already dispensed.');
        }

        $drug = \App\Models\Drug::where('name', $prescription->drug_name)->first();
        
        if (!$drug) {
            return back()->with('error', 'Drug not found in inventory.');
        }

        if ($drug->stock < $prescription->quantity) {
            return back()->with('error', 'Insufficient stock.');
        }

        $totalCost = $drug->price * $prescription->quantity;
        $patientWallet = $prescription->visit->patient->wallet;

        if (!$patientWallet || $patientWallet->balance < $totalCost) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        // Deduct Stock
        $drug->decrement('stock', $prescription->quantity);

        // Deduct Wallet
        $patientWallet->decrement('balance', $totalCost);

        // Update Prescription
        $prescription->update([
            'status' => 'dispensed',
            'dispensed_at' => now(),
            'dispensed_by' => Auth::user()->name,
            'unit_price' => $drug->price,
            'total_cost' => $totalCost,
        ]);

        return redirect()->route('pharmacy.portal.dashboard')->with('status', 'Prescription dispensed successfully.');
    }

    public function inventory()
    {
        $drugs = \App\Models\Drug::orderBy('name')->paginate(20);
        return view('pharmacy.inventory.index', compact('drugs'));
    }

    public function updateStock(Request $request, \App\Models\Drug $drug)
    {
        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $drug->update(['stock' => $data['stock']]);

        return back()->with('status', 'Stock updated successfully.');
    }
}
