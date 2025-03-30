<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): array
    {
        $user = User::firstWhere('email', $request->string('email'));

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

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
