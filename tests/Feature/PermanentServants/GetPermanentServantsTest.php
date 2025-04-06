<?php

declare(strict_types = 1);

use App\Models\Assignment;
use App\Models\PermanentServants;
use App\Models\Person;
use App\Models\PersonsPhoto;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\getJson;

beforeEach(fn () => login());

it('deve retornar os dados detalhados de um servidor permanente', function () {
    Storage::fake('minio');

    $unit = Unit::factory()->create(['nome' => 'Unidade Central']);

    $person = Person::factory()->create([
        'nome'            => 'João da Silva',
        'data_nascimento' => now()->subYears(30),
    ]);

    PersonsPhoto::factory()->create([
        'pes_id' => $person->id,
        'hash'   => 'foto123.jpg',
    ]);

    Assignment::factory()->create([
        'pes_id'       => $person->id,
        'unid_id'      => $unit->id,
        'data_lotacao' => '2020-01-01',
        'data_remocao' => null,
        'portaria'     => '123/2020',
    ]);

    $servant = PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.show', $servant->id));

    $sut->assertOk()
        ->assertJson([
            'data' => [
                'id'      => $servant->id,
                'pes_id'  => $person->id,
                'nome'    => 'João da Silva',
                'idade'   => 30,
                'unidade' => [
                    'id'    => $unit->id,
                    'nome'  => $unit->nome,
                    'sigla' => $unit->sigla,
                ],
                'lotacao' => [
                    'id'           => $person->assignment->id,
                    'pes_id'       => $person->id,
                    'unid_id'      => $unit->id,
                    'portaria'     => '123/2020',
                    'data_lotacao' => '2020-01-01',
                    'data_remocao' => null,
                ],
            ],
        ])
        ->assertJsonPath('data.fotografia', fn ($url) => str_contains($url, 'foto123.jpg'));
});
