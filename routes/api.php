<?php

declare(strict_types = 1);

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PermanentServantsController;
use App\Http\Controllers\TemporaryServantsController;
use App\Http\Controllers\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::name('api.')->middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('/permanent-servants', PermanentServantsController::class)
        ->only(['index', 'store']);

    Route::get('/permanent-servants/{permanentServants}', [PermanentServantsController::class, 'show']);

    Route::put('/permanent-servants/{permanentServants}', [PermanentServantsController::class, 'update']);

    Route::apiResource('/temporary-servants', TemporaryServantsController::class)
        ->only(['index', 'store']);

    Route::get('/temporary-servants/{temporaryServants}', [TemporaryServantsController::class, 'show']);

    Route::put('/temporary-servants/{temporaryServants}', [TemporaryServantsController::class, 'update']);

    Route::apiResource('/units', UnitController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::apiResource('/assignment', AssignmentController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::post('upload', [FileUploadController::class, 'upload']);
});
