<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->userTopup->topup($request->user(), $request->amount);

        return response('', 204);
    }
}
