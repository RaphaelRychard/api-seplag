<?php

declare(strict_types = 1);

use App\Models\User;

use function Pest\Laravel\postJson;

it('should be able to authenticate and return a token', function () {
    User::factory()->create([
        'email'    => 'raph@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = postJson('/api/login', [
        'email'    => 'raph@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)->assertJsonStructure(['token']);
});

it('should be able to fail with invalid credentials', function () {
    $response = postJson('/api/login', [
        'email'    => 'wrong@example.com',
        'password' => 'invalid',
    ]);

    $response->assertStatus(401)->assertJson(['error' => 'Invalid credentials']);
});
