<?php

declare(strict_types = 1);

use App\Models\Assignment;
use App\Models\PermanentServants;
use App\Models\Person;
use App\Models\PersonsPhoto;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\getJson;

beforeEach(fn (): Illuminate\Contracts\Auth\Authenticatable => login());

it('should be able to return detailed information of a permanent servant', function (): void {
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
        ->assertJsonPath('data.fotografia', fn ($url): bool => str_contains((string)$url, 'foto123.jpg'));
});

it('should be able to return null for unit and assignment when there is no active assignment', function (): void {
    $person = Person::factory()->create([
        'nome'            => 'Maria Souza',
        'data_nascimento' => now()->subYears(40),
    ]);

    PersonsPhoto::factory()->create([
        'pes_id' => $person->id,
        'hash'   => 'foto_maria.jpg',
    ]);

    Assignment::factory()->create([
        'pes_id'       => $person->id,
        'unid_id'      => Unit::factory()->create()->id,
        'data_lotacao' => '2015-05-10',
        'data_remocao' => now()->toDateString(),
    ]);

    $servant = PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.show', $servant->id));

    $sut->assertOk()
        ->assertJson([
            'data' => [
                'id'      => $servant->id,
                'pes_id'  => $person->id,
                'nome'    => 'Maria Souza',
                'unidade' => null,
                'lotacao' => null,
            ],
        ])
        ->assertJsonPath('data.fotografia', fn ($url): bool => str_contains((string)$url, 'foto_maria.jpg'));
});

it('should be able to return null photo when person has no photo registered', function (): void {
    $unit = Unit::factory()->create();

    $person = Person::factory()->create([
        'nome'            => 'Carlos Mendes',
        'data_nascimento' => now()->subYears(50),
    ]);

    Assignment::factory()->create([
        'pes_id'       => $person->id,
        'unid_id'      => $unit->id,
        'portaria'     => '456/2021',
        'data_lotacao' => '2021-02-02',
        'data_remocao' => null,
    ]);

    $servant = PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.show', $servant->id));

    $sut->assertOk()
        ->assertJson([
            'data' => [
                'id'         => $servant->id,
                'pes_id'     => $person->id,
                'nome'       => 'Carlos Mendes',
                'fotografia' => null,
            ],
        ]);
});
