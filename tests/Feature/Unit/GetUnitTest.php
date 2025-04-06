<?php

declare(strict_types = 1);

use App\Models\Unit;

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\getJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to get unit details when a valid ID is provided', function (): void {
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

it('should not be able to fetch a unit with an invalid ID', function (): void {
    getJson(route('api.units.show', 9999))
        ->assertNotFound();
});
