<?php

declare(strict_types = 1);

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): array => [config('app.name')]);

Route::get('/users', function () {
    return User::paginate();
});
