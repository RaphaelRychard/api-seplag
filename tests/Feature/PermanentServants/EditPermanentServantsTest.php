<?php

declare(strict_types = 1);

use App\Models\PermanentServants;
use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to edit a permanent servant', function (): void {
    $servant = PermanentServants::factory()->create([
        'se_matricula' => 'SEF-2025010',
    ]);

    $data = [
        'nome'            => 'Nome Atualizado',
        'data_nascimento' => '1980-01-01',
        'sexo'            => 'Outro',
        'mae'             => 'Mãe Atualizada',
        'pai'             => 'Pai Atualizado',
        'se_matricula'    => 'SEF-2025099',
    ];

    $sut = putJson(route('api.permanent-servants.update', $servant), $data);

    $sut->assertOk()
        ->assertJson([
            'data' => [
                'nome'            => 'Nome Atualizado',
                'data_nascimento' => '1980-01-01',
                'sexo'            => 'Outro',
                'mae'             => 'Mãe Atualizada',
                'pai'             => 'Pai Atualizado',
                'se_matricula'    => 'SEF-2025099',
            ],
        ]);

    assertDatabaseHas('pessoa', [
        'nome'            => 'Nome Atualizado',
        'data_nascimento' => '1980-01-01',
        'sexo'            => 'Outro',
        'mae'             => 'Mãe Atualizada',
        'pai'             => 'Pai Atualizado',
    ]);

    assertDatabaseHas('servidor_efetivo', [
        'se_matricula' => 'SEF-2025099',
    ]);
});

it('should validate required fields on update', function (): void {
    $servant = PermanentServants::factory()->create();

    $sut = putJson(route('api.permanent-servants.update', $servant), []);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors([
        'nome',
        'data_nascimento',
        'sexo',
        'mae',
        'pai',
        'se_matricula',
    ]);
});

it('should not be able to update servant with duplicate se_matricula', function (): void {
    PermanentServants::factory()->create([
        'se_matricula' => 'SEF-2025001',
    ]);

    $servant = PermanentServants::factory()->create([
        'se_matricula' => 'SEF-2025010',
    ]);

    $data = [
        'nome'            => 'Novo Nome',
        'data_nascimento' => '1991-01-01',
        'sexo'            => 'Masculino',
        'mae'             => 'Outra Mãe',
        'pai'             => 'Outro Pai',
        'se_matricula'    => 'SEF-2025001',
    ];

    $sut = putJson(route('api.permanent-servants.update', $servant), $data);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors(['se_matricula']);
});
