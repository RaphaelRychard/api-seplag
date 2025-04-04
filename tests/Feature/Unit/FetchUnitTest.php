<?php

declare(strict_types = 1);

use App\Models\Unit;

use function Pest\Laravel\getJson;

beforeEach(fn () => login());

it('should be able to fetch all units', function (): void {
    $units = Unit::factory(10)->create();

    $sut = getJson(route('api.units.index'));
    $sut->assertOk();

    $units->each(function ($unit) use ($sut) {
        $sut->assertJsonFragment([
            'id'    => $unit->id,
            'nome'  => $unit->nome,
            'sigla' => $unit->sigla,
        ]);
    });
});

it('should be able to fetch paginated units', function (): void {
    Unit::factory(15)->create();

    $sut = getJson(route('api.units.index'));
    $sut->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonFragment([
            'total'        => 15,
            'per_page'     => 10,
            'current_page' => 1,
            'last_page'    => 2,
            'from'         => 1,
            'to'           => 10,
        ]);

    $sutPage2 = getJson(route('api.units.index', ['page' => 2]));
    $sutPage2->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment([
            'total'        => 15,
            'per_page'     => 10,
            'current_page' => 2,
            'last_page'    => 2,
            'from'         => 11,
            'to'           => 15,
        ]);
});

it('should return empty pagination when no units exist', function (): void {
    $sut = getJson(route('api.units.index'));
    $sut->assertOk()
        ->assertJsonCount(0, 'data')
        ->assertJsonFragment([
            'total'        => 0,
            'per_page'     => 10,
            'current_page' => 1,
            'last_page'    => 1,
            'from'         => null,
            'to'           => null,
        ]);
});
