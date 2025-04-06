<?php

declare(strict_types = 1);

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to create a new permanent servant', function (): void {
    $data = [
        'nome'            => 'João da Silva',
        'data_nascimento' => '1990-05-20',
        'sexo'            => 'Masculino',
        'mae'             => 'Maria Silva',
        'pai'             => 'José da Silva',
        'se_matricula'    => 'SEF-2025001',
    ];

    $sut = postJson(route('api.permanent-servants.store'), $data);
    $sut->assertCreated();

    assertDatabaseHas('pessoa', [
        'nome'            => 'João da Silva',
        'data_nascimento' => '1990-05-20',
    ]);

    assertDatabaseHas('servidor_efetivo', [
        'se_matricula' => 'SEF-2025001',
    ]);
});

it('should not allow creation with duplicate se_matricula', function (): void {
    $data = [
        'nome'            => 'Carlos Mendes',
        'data_nascimento' => '1988-03-10',
        'sexo'            => 'Masculino',
        'mae'             => 'Helena Mendes',
        'pai'             => 'João Mendes',
        'se_matricula'    => 'SEF-2025002',
    ];

    postJson(route('api.permanent-servants.store'), $data)->assertCreated();

    $sut = postJson(route('api.permanent-servants.store'), $data);
    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors(['se_matricula']);
});

it('should validate presence of required fields', function (): void {
    $sut = postJson(route('api.permanent-servants.store'), []);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors([
        'nome',
        'data_nascimento',
        'sexo',
        'mae',
        'se_matricula',
    ]);
});

it('should be able to create servant with sexo as Masculino', function (): void {
    $data = [
        'nome'            => 'Lucas Alves',
        'data_nascimento' => '1991-01-15',
        'sexo'            => 'Masculino',
        'mae'             => 'Clara Alves',
        'pai'             => 'João Alves',
        'se_matricula'    => 'SEF-2025006',
    ];

    postJson(route('api.permanent-servants.store'), $data)
        ->assertCreated();

    assertDatabaseHas('pessoa', [
        'nome' => 'Lucas Alves',
        'sexo' => 'Masculino',
    ]);
});

it('should reject invalid sexo value', function (): void {
    $data = [
        'nome'            => 'Marcos Lima',
        'data_nascimento' => '1982-04-10',
        'sexo'            => 'Indefinido',
        'mae'             => 'Vera Lima',
        'pai'             => 'Paulo Lima',
        'se_matricula'    => 'SEF-2025004',
    ];

    $sut = postJson(route('api.permanent-servants.store'), $data);
    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors(['sexo']);
});
