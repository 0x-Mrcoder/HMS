<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientWalletController extends Controller
{
    public function deposit(Request $request)
    {
        $patient = Auth::user()?->patient;
        abort_unless($patient && $patient->wallet, 403);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,pos,transfer,online'],
            'reference' => ['nullable', 'string', 'max:120'],
        ]);

        $wallet = $patient->wallet()->lockForUpdate()->first();
        $newBalance = $wallet->balance + (float) $data['amount'];

        $wallet->update(['balance' => $newBalance]);

        $wallet->transactions()->create([
            'transaction_type' => 'deposit',
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'balance_after' => $newBalance,
            'reference' => $data['reference'] ?? null,
            'performed_by' => $patient->full_name,
            'service' => 'Patient Wallet Funding',
            'description' => 'Self-service wallet top-up from patient portal.',
            'transacted_at' => now(),
        ]);

        return back()->with('status', 'Wallet funded successfully.');
    }

    public function transactions()
    {
        $patient = Auth::user()?->patient;
        abort_unless($patient && $patient->wallet, 403);

        $transactions = WalletTransaction::where('wallet_id', $patient->wallet->id)
            ->latest('transacted_at')
            ->paginate(20);

        return view('patient.wallet.transactions', compact('transactions', 'patient'));
    }
}
