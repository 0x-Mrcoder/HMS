<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Visit;
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
}
