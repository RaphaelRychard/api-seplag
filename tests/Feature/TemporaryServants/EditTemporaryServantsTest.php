<?php

declare(strict_types = 1);

use App\Models\TemporaryServants;
use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\putJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to update a temporary servant successfully', function (): void {
    $temporaryServant = TemporaryServants::factory()->create();
    $data             = [
        'nome'            => 'Maria Oliveira',
        'data_nascimento' => '1985-10-10',
        'sexo'            => 'Feminino',
        'mae'             => 'Ana Oliveira',
        'pai'             => 'Carlos Oliveira',
        'data_admissao'   => '2024-01-01',
        'data_demissao'   => '2025-01-01',
    ];

    $response = putJson(route('api.temporary-servants.update', $temporaryServant), $data);

    $response->assertOk();
    expect($response->json('data'))->toMatchArray($data);
});

it('should not update when name is empty', function (): void {
    $temporaryServant = TemporaryServants::factory()->create();

    $data = [
        'nome'            => '',
        'data_nascimento' => '1985-10-10',
        'sexo'            => 'Feminino',
        'mae'             => 'Ana Oliveira',
        'pai'             => 'Carlos Oliveira',
        'data_admissao'   => '2024-01-01',
        'data_demissao'   => '2025-01-01',
    ];

    $response = putJson(route('api.temporary-servants.update', $temporaryServant), $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['nome']);
});

it('should fail if dismissal date is before admission date', function (): void {
    $temporaryServant = TemporaryServants::factory()->create();

    $data = [
        'nome'            => 'Pedro Lima',
        'data_nascimento' => '1990-03-15',
        'sexo'            => 'Masculino',
        'mae'             => 'Laura Lima',
        'pai'             => 'João Lima',
        'data_admissao'   => '2024-06-01',
        'data_demissao'   => '2024-05-01',
    ];

    $response = putJson(route('api.temporary-servants.update', $temporaryServant), $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data_demissao']);
});
