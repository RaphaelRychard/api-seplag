<?php

declare(strict_types = 1);

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\PermanentServantsController;
use App\Http\Controllers\PhotographUploadController;
use App\Http\Controllers\ServantFunctionalAddressController;
use App\Http\Controllers\TemporaryServantsController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('auth:api')->group(function (): void {
    Route::apiResource('/permanent-servants', PermanentServantsController::class)
        ->only(['index', 'store', 'show', 'update']);

    Route::get('/permanent-servants/functional-address/search', [ServantFunctionalAddressController::class, 'search'])
        ->name('functional-address.search');

    Route::apiResource('/temporary-servants', TemporaryServantsController::class)
        ->only(['index', 'store', 'show', 'update']);

    Route::apiResource('/units', UnitController::class)
        ->only(['index', 'show', 'store', 'update']);

    Route::apiResource('/assignment', AssignmentController::class)
        ->only(['index', 'show', 'store', 'update']);

    Route::patch('/assignment/{assignment}/remove', [AssignmentController::class, 'remove'])
        ->name('assignment.remove');

    Route::post('/photograph', [PhotographUploadController::class, 'upload'])
        ->name('photograph.upload');
});
