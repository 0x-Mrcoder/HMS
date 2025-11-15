<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Patient;
use App\Models\Visit;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $metrics = [
            'total_patients' => Patient::count(),
            'active_visits' => Visit::where('status', '!=', 'completed')->count(),
            'wallet_balance' => Wallet::sum('balance'),
            'pending_tests' => Visit::where('status', 'pending')->count(),
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

        $upcomingVisits = Visit::with(['patient', 'department'])
            ->orderByDesc('scheduled_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'metrics',
            'departments',
            'lowBalanceWallets',
            'recentTransactions',
            'upcomingVisits'
        ));
    }
}
