<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserTransfer;

class TransferController extends Controller
{
    protected $user;
    protected $userTransfer;

    public function __construct(User $user, UserTransfer $userTransfer)
    {
        $this->user = $user;
        $this->userTransfer = $userTransfer;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_username' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response('Bad Request', 400);
        }
        
        if (! $toUser = $this->user->usernameExists($request->to_username)) {
            return response('Destination user not found', 404);
        }

        if (! $this->user->enoughBalance($request->user()->balance, $request->amount)) {
            return response('Insufficient sender balance', 400);
        }

        if ($this->user->maximumBalance($toUser->balance, $request->amount)) {
            return response('Insufficient receiver balance', 400);
        }

        $this->userTransfer->createTransfer($request->user(), $toUser, $request->amount);

        return response('', 204);
    }
}
