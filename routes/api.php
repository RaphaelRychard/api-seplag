<?php

declare(strict_types = 1);

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PermanentServantsController;
use App\Http\Controllers\TemporaryServantsController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('/permanent-servants', PermanentServantsController::class)
        ->only(['index', 'store', 'show', 'update']);

    Route::apiResource('/temporary-servants', TemporaryServantsController::class)
        ->only(['index', 'store', 'show', 'update']);

    Route::apiResource('/units', UnitController::class)
        ->only(['index', 'show', 'store', 'update']);

    Route::apiResource('/assignment', AssignmentController::class)
        ->only(['index', 'show', 'store', 'update']);

    Route::patch('/assignment/{assignment}/remove', [AssignmentController::class, 'remove'])
        ->name('assignment.remove');

    Route::post('upload', [FileUploadController::class, 'upload']);
});
