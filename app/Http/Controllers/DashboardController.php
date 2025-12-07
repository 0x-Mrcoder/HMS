<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FinancialRecord;
use App\Models\InsuranceClaim;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Surgery;
use App\Models\Visit;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if ($user->role === 'doctor') {
            return redirect()->route('doctor.portal.dashboard');
        }
        if ($user->role === 'pharmacy') {
            return redirect()->route('pharmacy.portal.dashboard');
        }
        if ($user->role === 'patient') {
            return redirect()->route('patient.portal.dashboard');
        }

        $metrics = [
            'total_patients' => Patient::count(),
            'active_visits' => Visit::where('status', '!=', 'completed')->count(),
            'wallet_balance' => Wallet::sum('balance'),
            'pending_tests' => LabTest::where('status', 'pending')->count(),
            'pending_claims' => InsuranceClaim::whereIn('claim_status', ['draft', 'submitted'])->count(),
            'scheduled_surgeries' => Surgery::whereIn('status', ['scheduled', 'in_progress'])->count(),
            'daily_income' => FinancialRecord::where('record_type', 'income')
                ->whereDate('recorded_at', today())
                ->sum('amount'),
        ];

        $departments = Department::withCount('visits')
            ->with(['services' => fn ($query) => $query->select('id', 'department_id')])
            ->get();

        $lowBalanceWallets = Wallet::with('patient')
            ->whereColumn('balance', '<=', 'low_balance_threshold')
            ->limit(5)
            ->get();

        $recentTransactions = WalletTransaction::with('wallet.patient')
            ->latest('transacted_at')
            ->limit(6)
            ->get();

        $recentSurgeries = Surgery::with('patient')
            ->latest('scheduled_at')
            ->limit(5)
            ->get();

        $openClaims = InsuranceClaim::with('patient')
            ->whereIn('claim_status', ['submitted', 'approved'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $channelSplit = FinancialRecord::select('payment_channel', \DB::raw('SUM(amount) as total'))
            ->groupBy('payment_channel')
            ->pluck('total', 'payment_channel');

        $upcomingVisits = Visit::with(['patient', 'department'])
            ->orderByDesc('scheduled_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'metrics',
            'departments',
            'lowBalanceWallets',
            'recentTransactions',
            'recentSurgeries',
            'openClaims',
            'channelSplit',
            'upcomingVisits'
        ));
    }
}
