<?php

declare(strict_types = 1);

use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): array => [config('app.name')]);

Route::prefix('/api')->name('api.')->group(function () {
    Route::get('/persons', [PersonController::class, 'index'])->name('persons.index');
    Route::post('/persons', [PersonController::class, 'store'])->name('persons.store');
    Route::get('/persons/{person}', [PersonController::class, 'show'])->name('persons.show');
    Route::put('/persons/{person}', [PersonController::class, 'update'])->name('persons.update');
});
