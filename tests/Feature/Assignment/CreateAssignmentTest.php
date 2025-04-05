<?php

declare(strict_types = 1);

use App\Models\Assignment;
use App\Models\Person;

use App\Models\Unit;

use Illuminate\Contracts\Auth\Authenticatable;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to create new assignment', function (): void {
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

it('should not allow duplicate assignment for same person and unit', function (): void {
    $person = Person::factory()->create();
    $unit   = Unit::factory()->create();

    postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit->id,
        'data_lotacao' => '2025-01-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-0001',
    ]);

    $sut = postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit->id,
        'data_lotacao' => '2025-02-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-0002',
    ]);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors(['pes_id']);
});

it('should not allow person to be assigned to multiple active units', function (): void {
    $person = Person::factory()->create();
    $unit1  = Unit::factory()->create();
    $unit2  = Unit::factory()->create();

    postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit1->id,
        'data_lotacao' => '2025-01-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-1111',
    ]);

    $sut = postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit2->id,
        'data_lotacao' => '2025-03-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-2222',
    ]);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors(['pes_id']);
});

it('should be able to assign a person to a new unit if previously removed from another', function (): void {
    $person = Person::factory()->create();
    $unit1  = Unit::factory()->create();
    $unit2  = Unit::factory()->create();

    $assignmentId = postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit1->id,
        'data_lotacao' => '2025-01-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-1111',
    ])->assertStatus(201)->json('data.id');

    Assignment::where('id', $assignmentId)->update([
        'data_remocao' => '2025-02-01',
    ]);

    assertDatabaseHas('lotacao', [
        'id'           => $assignmentId,
        'data_remocao' => '2025-02-01',
    ]);

    postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit2->id,
        'data_lotacao' => '2025-03-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-2222',
    ])->assertStatus(201);

    $this->assertDatabaseCount('lotacao', 2);
});

it('should not allow new assignment if previous one is still active', function (): void {
    $person = Person::factory()->create();
    $unit1  = Unit::factory()->create();
    $unit2  = Unit::factory()->create();

    postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit1->id,
        'data_lotacao' => '2025-01-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-1111',
    ])->assertStatus(201);

    $response = postJson(route('api.assignment.store'), [
        'pes_id'       => (string) $person->id,
        'unid_id'      => (string) $unit2->id,
        'data_lotacao' => '2025-03-01',
        'data_remocao' => null,
        'portaria'     => 'PORT-2222',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['pes_id']);
});
