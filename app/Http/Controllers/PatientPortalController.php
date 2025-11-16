<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\NursingNote;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class PatientPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'patient', 403);

        $patient = $user->patient()
            ->with(['wallet.transactions' => fn ($query) => $query->latest('transacted_at')->limit(6)])
            ->withCount(['visits'])
            ->firstOrFail();

        $wallet = $patient->wallet;
        $transactions = $wallet?->transactions ?? collect();

        $visits = $patient->visits()
            ->with(['department', 'service'])
            ->latest('scheduled_at')
            ->limit(5)
            ->get();

        $prescriptions = Prescription::with('visit.department')
            ->whereHas('visit', fn ($query) => $query->where('patient_id', $patient->id))
            ->latest()
            ->limit(5)
            ->get();

        $labTests = LabTest::with('visit.department')
            ->whereHas('visit', fn ($query) => $query->where('patient_id', $patient->id))
            ->latest()
            ->limit(5)
            ->get();

        $nursingNotes = NursingNote::with('visit.department')
            ->whereHas('visit', fn ($query) => $query->where('patient_id', $patient->id))
            ->latest('recorded_at')
            ->limit(5)
            ->get();

        $upcomingVisit = $patient->visits()
            ->where(function ($query) {
                $query->whereDate('scheduled_at', '>=', now())
                    ->orWhereNull('scheduled_at');
            })
            ->orderBy('scheduled_at')
            ->first();

        $walletAlert = $wallet
            ? max(0, ($patient->wallet_minimum_balance ?? 0) - (float) $wallet->balance)
            : null;

        return view('patient.dashboard', compact(
            'patient',
            'wallet',
            'transactions',
            'visits',
            'prescriptions',
            'labTests',
            'nursingNotes',
            'upcomingVisit',
            'walletAlert'
        ));
    }
}
