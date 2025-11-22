<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function show(Wallet $wallet)
    {
        $wallet->load([
            'patient',
            'transactions' => fn ($query) => $query->latest('transacted_at'),
        ]);

        return view('wallets.show', compact('wallet'));
    }

    public function storeTransaction(Request $request, Wallet $wallet)
    {
        $data = $request->validate([
            'transaction_type' => ['required', 'in:deposit,deduction,refund'],
            'payment_method' => ['required', 'in:wallet,cash,pos,transfer,online'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string'],
            'service' => ['nullable', 'string'],
        ]);

        $amount = (float) $data['amount'];

        if (in_array($data['transaction_type'], ['deduction']) && $wallet->balance < $amount) {
            return back()->withErrors(['amount' => 'Insufficient wallet balance for deduction.']);
        }

        $newBalance = match ($data['transaction_type']) {
            'deposit' => $wallet->balance + $amount,
            'refund' => $wallet->balance + $amount,
            default => $wallet->balance - $amount,
        };

        $wallet->update(['balance' => $newBalance]);

        $wallet->transactions()->create([
            ...$data,
            'balance_after' => $newBalance,
            'performed_by' => Auth::user()?->name ?? 'System Admin',
            'transacted_at' => now(),
        ]);

        return redirect()->route('wallets.show', $wallet)->with('status', 'Wallet updated successfully.');
    }
}
