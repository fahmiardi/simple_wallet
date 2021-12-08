<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        $request->validate([
            'username' => 'required'
        ]);

        if ($this->user->usernameExists($request->username)) {
            throw ValidationException::withMessages([
                'username' => ['username exists']
            ]);
        }

        $token = $this->user->register([
            'username' => $request->username
        ]);

        return response()->json([
            'token' => $token
        ], 201);
    }
}
