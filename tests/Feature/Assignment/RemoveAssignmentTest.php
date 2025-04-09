<?php

declare(strict_types = 1);

use App\Models\Assignment;
use App\Models\Person;

use App\Models\Unit;

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\patchJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to remove an assignment', function (): void {
    $person     = Person::factory()->create();
    $unit       = Unit::factory()->create();
    $assignment = Assignment::factory()->create([
        'pes_id'       => $person->id,
        'unid_id'      => $unit->id,
        'data_lotacao' => '2024-01-01',
        'data_remocao' => null,
    ]);

    $removalDate = now()->toDateString();

    $response = patchJson(route('api.assignment.remove', $assignment), [
        'data_remocao' => $removalDate,
    ]);

    $response->assertOk();
    $response->assertJson([
        'data' => [
            'mensagem' => 'Remoção feita com sucesso.',
        ],
    ]);

    assertDatabaseHas('lotacao', [
        'id'           => $assignment->id,
        'data_remocao' => $removalDate,
    ]);
});

it('should not allow removing an already removed assignment', function (): void {
    $assignment = Assignment::factory()->create([
        'data_remocao' => now()->subDay()->toDateString(),
    ]);

    $response = patchJson(route('api.assignment.remove', $assignment), [
        'data_remocao' => now()->toDateString(),
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data_remocao']);
});

it('should not allow removal with future date', function (): void {
    $assignment = Assignment::factory()->create([
        'data_remocao' => null,
    ]);

    $futureDate = now()->addDay()->toDateString();

    $response = patchJson(route('api.assignment.remove', $assignment), [
        'data_remocao' => $futureDate,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data_remocao']);
});
