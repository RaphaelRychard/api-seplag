<?php

declare(strict_types = 1);

use App\Models\Assignment;
use App\Models\PermanentServants;
use App\Models\Person;
use App\Models\PersonsPhoto;
use App\Models\Unit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\getJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to fetch permanent servants with pagination and filters', function (): void {
    $person = Person::factory()->create(['nome' => 'João da Silva']);

    PersonsPhoto::factory()->create(['pes_id' => $person->id]);
    Assignment::factory()->create(['pes_id' => $person->id]);
    PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.index', [
        'nome'     => 'João',
        'per_page' => 10,
        'with'     => 'person',
    ]));

    $sut->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'pes_id',
                    'nome',
                    'idade',
                    'fotografia',
                    'unidade_lotacao',
                ],
            ],
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to',
            ],
        ])
        ->assertJsonPath('data.0.nome', 'João da Silva');
});

it('should be able to paginate permanent servants', function (): void {
    Person::factory()->count(15)->create()->each(function ($person): void {
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.permanent-servants.index', ['per_page' => 10]));

    $sut->assertOk()
        ->assertJsonPath('pagination.per_page', 10)
        ->assertJsonCount(10, 'data');
});

it('should be able to return empty data and correct pagination when no permanent servant matches filters', function (): void {
    Person::factory()->count(5)->create()->each(function ($person): void {
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.permanent-servants.index', ['nome' => 'Inexistente']));

    $sut->assertOk()
        ->assertJson([
            'data'       => [],
            'pagination' => [
                'total'        => 0,
                'per_page'     => 10,
                'current_page' => 1,
                'last_page'    => 1,
                'from'         => null,
                'to'           => null,
            ],
        ]);
});

it('should be able to filter permanent servants by name', function (): void {
    $joao = Person::factory()->create(['nome' => 'João da Silva']);
    PermanentServants::factory()->create(['pes_id' => $joao->id]);

    Person::factory()->create(['nome' => 'Maria Oliveira']);
    PermanentServants::factory()->create();

    $sut = getJson(route('api.permanent-servants.index', ['nome' => 'João']));

    $sut->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.nome', 'João da Silva');
});

it('should be able to filter permanent servants by partial name among many records', function (): void {
    Person::factory()->count(15)->create()->each(function ($person): void {
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $joao = Person::factory()->create(['nome' => 'João da Silva']);
    PermanentServants::factory()->create(['pes_id' => $joao->id]);

    $sut = getJson(route('api.permanent-servants.index', ['nome' => 'João']));

    $sut->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.nome', 'João da Silva');
});

it('should be able to filter permanent servants by unit id among many records', function (): void {
    $unitA = Unit::factory()->create(['nome' => 'Unidade Alfa']);
    $unitB = Unit::factory()->create(['nome' => 'Unidade Beta']);

    Person::factory()->count(10)->create()->each(function ($person) use ($unitB): void {
        Assignment::factory()->create(['pes_id' => $person->id, 'unid_id' => $unitB->id]);
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $person = Person::factory()->create(['nome' => 'Carlos Pinto']);
    Assignment::factory()->create(['pes_id' => $person->id, 'unid_id' => $unitA->id]);
    PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.index', ['unid_id' => $unitA->id]));

    $sut->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.nome', 'Carlos Pinto');
});

it('should be able to filter permanent servants by name and unit id simultaneously', function (): void {
    $unitA = Unit::factory()->create(['nome' => 'Unidade Alfa']);
    $unitB = Unit::factory()->create(['nome' => 'Unidade Beta']);

    $joao = Person::factory()->create(['nome' => 'João da Silva']);
    Assignment::factory()->create(['pes_id' => $joao->id, 'unid_id' => $unitA->id]);
    PermanentServants::factory()->create(['pes_id' => $joao->id]);

    $joao2 = Person::factory()->create(['nome' => 'João Pereira']);
    Assignment::factory()->create(['pes_id' => $joao2->id, 'unid_id' => $unitB->id]);
    PermanentServants::factory()->create(['pes_id' => $joao2->id]);

    Person::factory()->count(5)->create()->each(function ($person) use ($unitA): void {
        Assignment::factory()->create(['pes_id' => $person->id, 'unid_id' => $unitA->id]);
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.permanent-servants.index', [
        'nome'    => 'João',
        'unid_id' => $unitA->id,
    ]));

    $sut->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.nome', 'João da Silva');
});

it('should be able to include person relationship when requested', function (): void {
    $person = Person::factory()->create(['nome' => 'João da Silva']);
    PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.index', ['with' => 'person']));

    $sut->assertOk()
        ->assertJsonPath('data.0.nome', 'João da Silva');
});

it('should be able to return fotografia URL if hash exists', function (): void {
    Storage::fake('minio');
    $person = Person::factory()->create(['nome' => 'Foto Teste']);
    $photo  = PersonsPhoto::factory()->create([
        'hash'   => 'teste-hash.jpg',
        'pes_id' => $person->id,
    ]);
    PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.index', ['with' => 'person.personsPhoto']));

    $sut->assertOk()
        ->assertJsonPath('data.0.nome', 'Foto Teste')
        ->assertJson(
            fn ($json) => $json->where('data.0.fotografia', fn ($url): bool => str_contains((string) $url, 'teste-hash'))
                ->etc()
        );
});

it('should return null fotografia if no photo is attached', function (): void {
    $person = Person::factory()->create();
    PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.index', ['with' => 'person']));

    $sut->assertOk()
        ->assertJsonPath('data.0.fotografia', null);
});

it('should return correct unidade_lotacao name if exists', function (): void {
    $unit   = Unit::factory()->create(['nome' => 'Unidade Central']);
    $person = Person::factory()->create();

    Assignment::factory()->create([
        'pes_id'  => $person->id,
        'unid_id' => $unit->id,
    ]);
    PermanentServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.permanent-servants.index', ['with' => 'person.assignment.unit']));

    $sut->assertOk()
        ->assertJsonPath('data.0.unidade_lotacao.nome', 'Unidade Central');
});
