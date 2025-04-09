<?php

declare(strict_types = 1);

use function Pest\Laravel\postJson;

use Tymon\JWTAuth\Facades\JWTAuth;

beforeEach(function (): void {
    $user       = login();
    $this->user = $user;
});

it('should be able to log out and invalidate the token', function (): void {
    $token = JWTAuth::fromUser($this->user);

    $response = postJson('/api/logout', [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Successfully logged out']);
});
