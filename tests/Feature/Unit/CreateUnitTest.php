<?php

declare(strict_types = 1);

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to create new units', function (): void {
    $data = [
        'nome'  => 'Unidade de test',
        'sigla' => 'teste',
    ];

    $sut = postJson(route('api.units.store'), $data);

    $sut->assertStatus(201);

    assertDatabaseHas('unidade', [
        'nome' => 'Unidade de test',
    ]);
});

it('should be able not allow duplicate units', function (): void {
    $data = [
        'nome'  => 'Unidade 1',
        'sigla' => 'TESTE',
    ];

    postJson(route('api.units.store'), $data)
        ->assertCreated();

    $sut = postJson(route('api.units.store'), [
        'nome'  => 'Unidade 2',
        'sigla' => 'TESTE',
    ]);

    $sut->assertStatus(422);

    assertDatabaseCount('unidade', 1);
});
