<?php

declare(strict_types = 1);

use App\Http\Controllers\PersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('/persons', PersonController::class)
        ->only(['index', 'show' , 'store', 'update']);
});
