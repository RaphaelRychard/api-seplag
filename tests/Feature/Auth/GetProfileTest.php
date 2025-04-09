<?php

declare(strict_types = 1);

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\getJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to return authenticated user data', function () {
    $response = getJson('/api/user');

    $response->assertStatus(200)
        ->assertJsonStructure(['id', 'name', 'email']);
});
