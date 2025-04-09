<?php

declare(strict_types = 1);

use function Pest\Laravel\postJson;

it('should be able to register a new user and return a token', function () {
    $response = postJson('/api/register', [
        'name'                  => 'John Doe',
        'email'                 => 'johndoe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200)->assertJsonStructure(['token']);
});
