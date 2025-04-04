<?php

declare(strict_types = 1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::post('/api/login', [AuthController::class, 'login']);
    Route::post('/api/register', [AuthController::class, 'register']);

    Route::get('/api/user', fn (Request $request) => $request->user())
        ->middleware('auth:sanctum');
});
