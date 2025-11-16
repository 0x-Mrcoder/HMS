<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class DoctorPortalController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->role === 'doctor', 403);

        $today = now()->startOfDay();

        $visits = Visit::with(['patient', 'department', 'service'])
            ->orderByDesc('scheduled_at')
            ->limit(8)
            ->get();

        $activeQueue = Visit::with(['patient', 'department'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $pendingPrescriptions = Prescription::with(['visit.patient'])
            ->where('status', 'pending')
            ->latest()
            ->limit(6)
            ->get();

        $recentLabTests = LabTest::with(['visit.patient'])
            ->latest()
            ->limit(5)
            ->get();

        $metrics = [
            'today_appointments' => Visit::whereDate('scheduled_at', $today)->count(),
            'active_patients' => Visit::whereIn('status', ['pending', 'in_progress'])->distinct('patient_id')->count(),
            'pending_prescriptions' => $pendingPrescriptions->count(),
            'pending_labs' => LabTest::where('status', 'pending')->count(),
        ];

        return view('doctor.dashboard', compact(
            'metrics',
            'visits',
            'activeQueue',
            'pendingPrescriptions',
            'recentLabTests'
        ));
    }
}
