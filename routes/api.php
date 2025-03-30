<?php

declare(strict_types = 1);

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PermanentServantsController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TemporaryServantsController;
use App\Http\Controllers\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('/persons', PersonController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::apiResource('/permanent-servants', PermanentServantsController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::apiResource('/temporary-servants', TemporaryServantsController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::apiResource('/units', UnitController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::apiResource('/assignment', AssignmentController::class)
        ->only(['index', 'show' , 'store', 'update']);

    Route::post('upload', [FileUploadController::class, 'upload']);
});
