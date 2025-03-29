<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->string('name'),
            'email'    => $request->string('email'),
            'password' => $request->string('password'),
        ]);

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }
}
