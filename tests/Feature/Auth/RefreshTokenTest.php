<?php

declare(strict_types = 1);

use function Pest\Laravel\postJson;

use Tymon\JWTAuth\Facades\JWTAuth;

it('should be able to refresh token for authenticated user', function (): void {
    $user  = login();
    $token = JWTAuth::fromUser($user);

    $response = postJson('/api/refresh', [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertStatus(200)->assertJsonStructure(['token']);
});

it('should return unauthenticated message when an invalid token is provided', function (): void {
    $invalidToken = 'this.is.an.invalid.token';

    $response = postJson('/api/refresh', [], [
        'Authorization' => "Bearer $invalidToken",
    ]);

    $response->assertStatus(401)->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});
