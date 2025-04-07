<?php

declare(strict_types = 1);

use App\Http\Resources\DetailsTemporaryServantResource;
use App\Models\Assignment;
use App\Models\Person;
use App\Models\PersonsPhoto;
use App\Models\TemporaryServants;
use App\Models\Unit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Storage;

beforeEach(fn (): Authenticatable => login());

it('should be able to transform temporary servant resource correctly', function () {
    Storage::shouldReceive('disk->temporaryUrl')
        ->once()
        ->andReturn('https://fake-minio-url.com/foto.jpg');

    $unit = Unit::factory()->create([
        'nome'  => 'Unidade Exemplo',
        'sigla' => 'UEX',
    ]);

    $assignment = Assignment::factory()->create([
        'unid_id'      => $unit->id,
        'data_lotacao' => '2020-01-01',
        'data_remocao' => null,
        'portaria'     => '123/2020',
    ]);

    $photo = PersonsPhoto::factory()->create([
        'hash' => 'foto123',
    ]);

    $person = Person::factory()->create([
        'nome'            => 'João Silva',
        'data_nascimento' => now()->subYears(30)->toDateString(),
    ]);

    $person->setRelation('assignment', $assignment);
    $person->setRelation('personsPhoto', $photo);
    $assignment->setRelation('unit', $unit);

    $temporaryServant = TemporaryServants::factory()->create([
        'pes_id' => $person->id,
    ]);
    $temporaryServant->setRelation('person', $person);

    $resource = DetailsTemporaryServantResource::make($temporaryServant)->resolve();

    expect($resource)->toMatchArray([
        'id'         => $temporaryServant->id,
        'pes_id'     => $temporaryServant->pes_id,
        'nome'       => 'João Silva',
        'idade'      => 30,
        'fotografia' => 'https://fake-minio-url.com/foto.jpg',
        'unidade'    => [
            'id'    => $unit->id,
            'nome'  => 'Unidade Exemplo',
            'sigla' => 'UEX',
        ],
        'lotacao' => [
            'id'           => $assignment->id,
            'pes_id'       => $assignment->pes_id,
            'unid_id'      => $assignment->unid_id,
            'data_lotacao' => '2020-01-01',
            'data_remocao' => null,
            'portaria'     => '123/2020',
        ],
    ]);
});

it('should be able to return null for fotografia when person has no photo', function () {
    $unit = Unit::factory()->create();

    $assignment = Assignment::factory()->create([
        'unid_id'      => $unit->id,
        'data_lotacao' => '2020-01-01',
        'data_remocao' => null,
        'portaria'     => '123/2020',
    ]);

    $person = Person::factory()->create([
        'nome'            => 'Maria Teste',
        'data_nascimento' => now()->subYears(25)->toDateString(),
    ]);

    $person->setRelation('assignment', $assignment);
    $assignment->setRelation('unit', $unit);

    $temporaryServant = TemporaryServants::factory()->create([
        'pes_id' => $person->id,
    ]);
    $temporaryServant->setRelation('person', $person);

    $resource = DetailsTemporaryServantResource::make($temporaryServant)->resolve();

    expect($resource)->toMatchArray([
        'id'         => $temporaryServant->id,
        'pes_id'     => $temporaryServant->pes_id,
        'nome'       => 'Maria Teste',
        'idade'      => 25,
        'fotografia' => null,
    ]);
});

it('should be able to return null for unidade and lotacao when person has no assignment', function () {
    $person = Person::factory()->create([
        'nome'            => 'Carlos Sem Lotação',
        'data_nascimento' => now()->subYears(40)->toDateString(),
    ]);

    $temporaryServant = TemporaryServants::factory()->create([
        'pes_id' => $person->id,
    ]);
    $temporaryServant->setRelation('person', $person);

    $resource = DetailsTemporaryServantResource::make($temporaryServant)->resolve();

    expect($resource)->toMatchArray([
        'id'         => $temporaryServant->id,
        'pes_id'     => $temporaryServant->pes_id,
        'nome'       => 'Carlos Sem Lotação',
        'idade'      => 40,
        'fotografia' => null,
        'unidade'    => null,
        'lotacao'    => null,
    ]);
});
