<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required'
        ]);

        if ($validator->fails()) {
            return response('Bad Request', 400);
        }

        if ($this->user->usernameExists($request->username)) {
            return response('Username already exists', 409);
        }

        $token = $this->user->register([
            'username' => $request->username
        ]);

        return response()->json([
            'token' => $token
        ], 201);
    }
}
