<?php

declare(strict_types = 1);

use App\Models\Person;

use App\Models\Unit;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(fn () => login());

it('should be able to edit new assignment', function (): void {
    $person = Person::factory()->create();
    $unit   = Unit::factory()->create();

    $data = [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit->id,
        'data_lotacao' => '2025-01-01',
        'data_remocao' => '2025-01-01',
        'portaria'     => 'PORT-1234-A',

    ];

    $sut = postJson(route('api.assignment.store'), $data);

    $sut->assertStatus(201);

    assertDatabaseHas('lotacao', [
        'pes_id'  => (string) $person->id,
        'unid_id' => (string) $unit->id,
    ]);
});
