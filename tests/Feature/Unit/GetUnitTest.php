<?php

declare(strict_types = 1);

use App\Models\Unit;

use function Pest\Laravel\getJson;

beforeEach(fn () => login());

it('should be able to show a unit', function (): void {
    $unit = Unit::factory()->create([
        'nome'  => 'Unidade Exemplo',
        'sigla' => 'UEX',
    ]);

    $sut = getJson(route('api.units.show', $unit->id));

    $sut->assertOk()
        ->assertJsonFragment([
            'id'    => $unit->id,
            'nome'  => 'Unidade Exemplo',
            'sigla' => 'UEX',
        ]);
});

it('should return 404 when trying to show a non-existent unit', function (): void {
    $sut = getJson(route('api.units.show', 9999));

    $sut->assertStatus(404);
});
