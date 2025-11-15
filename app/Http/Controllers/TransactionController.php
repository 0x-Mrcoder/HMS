<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->string('type')->toString();

        $transactions = WalletTransaction::with('wallet.patient')
            ->when($type, fn ($query) => $query->where('transaction_type', $type))
            ->latest('transacted_at')
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'type'));
    }
}
