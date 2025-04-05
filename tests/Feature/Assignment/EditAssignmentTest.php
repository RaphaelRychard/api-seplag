<?php

declare(strict_types = 1);

use App\Models\Assignment;
use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\putJson;

beforeEach(fn (): Authenticatable => login());

it('should update an assignment with valid data', function () {
    $assignment = Assignment::factory()->create();

    $payload = [
        'data_lotacao' => '2024-10-01',
        'portaria'     => 'Nova Portaria 123',
    ];

    $response = putJson(route('api.assignment.update', $assignment->id), $payload);

    $response->assertOk()
        ->assertJsonFragment([
            'id'           => $assignment->id,
            'data_lotacao' => '2024-10-01',
            'portaria'     => 'Nova Portaria 123',
        ]);

    $this->assertDatabaseHas('lotacao', [
        'id'           => $assignment->id,
        'data_lotacao' => '2024-10-01',
        'portaria'     => 'Nova Portaria 123',
    ]);
});

it('should return validation errors when data is missing or invalid', function () {
    $assignment = Assignment::factory()->create();

    $response = putJson(route('api.assignment.update', $assignment->id), []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['data_lotacao', 'portaria']);
});

it('should return validation error when portaria is too short', function () {
    $assignment = Assignment::factory()->create();

    $response = putJson(route('api.assignment.update', $assignment->id), [
        'data_lotacao' => '2024-10-01',
        'portaria'     => 'ab',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['portaria']);
});
