<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\UserTopup;

class BalanceController extends Controller
{
    protected $userTopup;

    public function __construct(UserTopup $userTopup)
    {
        $this->userTopup = $userTopup;
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        if ($newBalance = $this->userTopup->maximumBalance($request->user()->balance, $request->amount)) {
            throw ValidationException::withMessages([
                'amount' => ['balance must lower than: '.number_format(config('app.max_balance')).'. New balance: '.number_format($newBalance)]
            ]);
        }
        
        $this->userTopup->topup($request->user(), $request->amount);

        return response('', 204);
    }

    public function show(Request $request)
    {
        return response()->json([
            'balance' => (float) $request->user()->balance,
        ]);
    }
}
