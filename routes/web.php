<?php

declare(strict_types = 1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::post('/api/login', [AuthController::class, 'login']);
    Route::post('/api/register', [AuthController::class, 'register']);
});

Route::middleware('auth:api')->group(function (): void {
    Route::post('/api/refresh', [AuthController::class, 'refresh']);
    Route::post('/api/logout', [AuthController::class, 'logout']);
    Route::get('/api/user', [AuthController::class, 'me']);
});
