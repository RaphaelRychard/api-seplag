<?php

declare(strict_types = 1);

use App\Models\Assignment;
use App\Models\Person;
use App\Models\PersonsPhoto;
use App\Models\TemporaryServants;
use App\Models\Unit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\getJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to fetch temporary servants with pagination and structure', function (): void {
    Person::factory()->count(15)->create()->each(function ($person): void {
        TemporaryServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.temporary-servants.index', ['per_page' => 10]));

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
        ->assertJsonPath('pagination.per_page', 10)
        ->assertJsonCount(10, 'data');
});

it('should be able to filter temporary servants by unit id', function (): void {
    $unitA = Unit::factory()->create(['nome' => 'Unit A']);
    $unitB = Unit::factory()->create(['nome' => 'Unit B']);

    Person::factory()->count(10)->create()->each(function ($person) use ($unitB): void {
        Assignment::factory()->create([
            'pes_id'  => $person->id,
            'unid_id' => $unitB->id,
        ]);
        TemporaryServants::factory()->create(['pes_id' => $person->id]);
    });

    $person = Person::factory()->create(['nome' => 'Alice']);
    Assignment::factory()->create([
        'pes_id'  => $person->id,
        'unid_id' => $unitA->id,
    ]);
    TemporaryServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.temporary-servants.index', ['unid_id' => $unitA->id]));

    $sut->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.nome', 'Alice');
});

it('should be able to include person relationship when requested', function (): void {
    $person = Person::factory()->create(['nome' => 'Bob']);
    TemporaryServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.temporary-servants.index', ['with' => 'person']));

    $sut->assertOk()
        ->assertJsonPath('data.0.nome', 'Bob');
});

it('should be able to navigate to a specific page in pagination', function (): void {
    Person::factory()->count(25)->create()->each(function ($person): void {
        TemporaryServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.temporary-servants.index', [
        'per_page' => 10,
        'page'     => 2,
    ]));

    $sut->assertOk()
        ->assertJsonPath('pagination.current_page', 2)
        ->assertJsonPath('pagination.per_page', 10)
        ->assertJsonCount(10, 'data');
});

it('should return empty data when there are no temporary servants', function (): void {
    $sut = getJson(route('api.temporary-servants.index'));

    $sut->assertOk()
        ->assertJsonCount(0, 'data')
        ->assertJsonPath('pagination.total', 0);
});

it('should return a temporary URL for fotografia when photo exists', function (): void {
    Storage::fake('minio');

    $person = Person::factory()->create(['nome' => 'Foto Teste']);
    $photo  = PersonsPhoto::factory()->create([
        'pes_id' => $person->id,
        'hash'   => 'foto123.jpg',
    ]);

    TemporaryServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.temporary-servants.index'));

    $sut->assertOk()
        ->assertJsonPath('data.0.fotografia', Storage::disk('minio')->temporaryUrl("uploads/{$photo->hash}", now()->addMinutes(5)));
});

it('should return data with multiple includes (person and assignment)', function (): void {
    $unit   = Unit::factory()->create(['nome' => 'Lotação XPTO']);
    $person = Person::factory()->create(['nome' => 'Incluído']);
    Assignment::factory()->create([
        'pes_id'  => $person->id,
        'unid_id' => $unit->id,
    ]);
    TemporaryServants::factory()->create(['pes_id' => $person->id]);

    $sut = getJson(route('api.temporary-servants.index', ['with' => 'person,assignment']));

    $sut->assertOk()
        ->assertJsonPath('data.0.nome', 'Incluído')
        ->assertJsonPath('data.0.unidade_lotacao.nome', 'Lotação XPTO');
});
