<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserTopup;

class BalanceController extends Controller
{
    protected $user;
    protected $userTopup;

    public function __construct(User $user, UserTopup $userTopup)
    {
        $this->user = $user;
        $this->userTopup = $userTopup;
    }

    public function topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response('Invalid topup amount', 400);
        }

        if ($this->user->maximumBalance($request->user()->balance, $request->amount)) {
            return response('Invalid topup amount', 400);
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
