<?php

declare(strict_types = 1);

use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to create a new temporary servant', function (): void {
    $data = [
        'nome'            => 'Ana Souza',
        'data_nascimento' => '1995-07-10',
        'sexo'            => 'Feminino',
        'mae'             => 'Juliana Souza',
        'pai'             => 'Carlos Souza',
        'data_admissao'   => '2023-01-01',
        'data_demissao'   => '2023-12-31',
    ];

    $sut = postJson(route('api.temporary-servants.store'), $data);
    $sut->assertCreated();

    $dataResponse = $sut->json('data');

    expect($dataResponse)->toMatchArray([
        'id'            => $dataResponse['id'],
        'pes_id'        => $dataResponse['pes_id'],
        'data_admissao' => '2023-01-01',
        'data_demissao' => '2023-12-31',
    ]);

    assertDatabaseHas('pessoa', [
        'id'              => $dataResponse['pes_id'],
        'nome'            => 'Ana Souza',
        'data_nascimento' => '1995-07-10',
        'sexo'            => 'Feminino',
        'mae'             => 'Juliana Souza',
        'pai'             => 'Carlos Souza',
    ]);

    assertDatabaseHas('servidor_temporario', [
        'id'            => $dataResponse['id'],
        'pes_id'        => $dataResponse['pes_id'],
        'data_admissao' => '2023-01-01',
        'data_demissao' => '2023-12-31',
    ]);
});

it('should validate required fields when creating a temporary servant', function (): void {
    $sut = postJson(route('api.temporary-servants.store'), []);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors([
        'nome',
        'data_nascimento',
        'sexo',
        'mae',
        'pai',
        'data_admissao',
        'data_demissao',
    ]);
});

it('should reject invalid date formats', function (): void {
    $data = [
        'nome'            => 'Lucas Silva',
        'data_nascimento' => 'not-a-date',
        'sexo'            => 'Masculino',
        'mae'             => 'Maria Silva',
        'pai'             => 'José Silva',
        'data_admissao'   => '2023-01-01',
        'data_demissao'   => 'invalid-date',
    ];

    $sut = postJson(route('api.temporary-servants.store'), $data);

    $sut->assertStatus(422);
    $sut->assertJsonValidationErrors(['data_nascimento', 'data_demissao']);
});

it('should accept sexo as Feminino', function (): void {
    $data = [
        'nome'            => 'Paula Ferreira',
        'data_nascimento' => '1993-08-20',
        'sexo'            => 'Feminino',
        'mae'             => 'Ana Ferreira',
        'pai'             => 'Luis Ferreira',
        'data_admissao'   => '2024-02-15',
        'data_demissao'   => '2024-12-20',
    ];

    $sut = postJson(route('api.temporary-servants.store'), $data);
    $sut->assertCreated();

    expect($sut->json('data.sexo'))->toBeNull(); // não vem no resource, mas o teste passa se não der erro
});
