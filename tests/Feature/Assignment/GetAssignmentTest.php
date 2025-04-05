<?php

declare(strict_types = 1);

use App\Models\Assignment;

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\getJson;

beforeEach(fn (): Authenticatable => login());

it('should return assignment details when given a valid ID', function () {
    $assignment = Assignment::factory()->create([
        'data_lotacao' => '2024-10-01',
        'data_remocao' => '2025-01-01',
        'portaria'     => 'Portaria XYZ',
    ]);

    $response = getJson(route('api.assignment.show', $assignment->id));

    $response->assertOk()
        ->assertJsonFragment([
            'id'           => $assignment->id,
            'pes_id'       => $assignment->pes_id,
            'unid_id'      => $assignment->unid_id,
            'data_lotacao' => '2024-10-01',
            'data_remocao' => '2025-01-01',
            'portaria'     => 'Portaria XYZ',
        ]);
});

it('should return 404 when assignment does not exist', function () {
    $response = getJson(route('api.assignment.show', 9999));

    $response->assertNotFound();
});
