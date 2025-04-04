<?php

declare(strict_types = 1);

use App\Models\Unit;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

beforeEach(fn () => login());

it('should be able to edit a unit', function (): void {
    $unit = Unit::factory()->create([
        'nome'  => 'Unidade de test',
        'sigla' => 'teste',
    ]);

    $updateData = [
        'nome'  => 'Unidade editada',
        'sigla' => 'EDIT',
    ];

    $sut = putJson(route('api.units.update', $unit->id), $updateData);
    $sut->assertStatus(200);

    assertDatabaseHas('unidade', [
        'id'   => $unit->id,
        'nome' => 'Unidade editada',
    ]);
});

it('should not allow editing a unit with duplicate sigla', function (): void {
    $unitA = Unit::factory()->create(['sigla' => 'ORIGINAL']);
    $unitB = Unit::factory()->create(['sigla' => 'DUPLICADA']);

    $sut = putJson(route('api.units.update', $unitA->id), [
        'nome'  => 'Qualquer Nome',
        'sigla' => 'DUPLICADA',
    ]);

    $sut->assertStatus(422);

    assertDatabaseHas('unidade', [
        'id'    => $unitA->id,
        'sigla' => 'ORIGINAL',
    ]);
});
