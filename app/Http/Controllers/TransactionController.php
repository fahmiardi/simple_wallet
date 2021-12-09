<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function topTransactionsByUser(Request $request)
    {
        return response()->json($this->transaction->transactionsByUser($request->user()));
    }

    public function topUsers()
    {
        return response()->json($this->transaction->transactionsByValue());
    }
}
