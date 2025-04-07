<?php

declare(strict_types = 1);

use App\Models\Address;
use App\Models\AddressUnit;
use App\Models\Assignment;
use App\Models\City;
use App\Models\PermanentServants;
use App\Models\Person;
use App\Models\Unit;
use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\getJson;


beforeEach(fn (): Authenticatable => login());

it('should return validation error when nome parameter is missing', function (): void {
    $sut = getJson(route('api.functional-address.search'));

    $sut->assertStatus(422);
    $sut->assertJsonFragment([
        'message' => 'The nome field is required.',
    ]);
});

it('should be able to return functional address for a matching permanent servant', function (): void {
    $city = City::factory()->create(['nome' => 'CityName']);

    $address = Address::factory()->create([
        'tipo_logradouro' => 'Rua',
        'logradouro'      => 'Das Flores',
        'numero'          => 123,
        'bairro'          => 'Centro',
        'cid_id'          => $city->id,
    ]);

    $addressUnit = AddressUnit::factory()->create([
        'end_id' => $address->id,
    ]);

    $unit = Unit::factory()->create([
        'nome'  => 'Unidade Central',
        'sigla' => 'UC',
    ]);

    $addressUnit->update(['unid_id' => $unit->id]);

    $person = Person::factory()->create([
        'nome'            => 'João da Silva',
        'data_nascimento' => '1990-05-20',
    ]);

    Assignment::factory()->create([
        'pes_id'       => $person->id,
        'unid_id'      => $unit->id,
        'data_lotacao' => '2020-01-01',
        'data_remocao' => null,
        'portaria'     => '123/2020',
    ]);

    PermanentServants::factory()->create([
        'pes_id' => $person->id,
    ]);

    $sut = getJson(route('api.functional-address.search', [
        'nome'     => 'João',
        'per_page' => 10,
    ]));

    $sut->assertOk();

    $sut->assertJsonFragment([
        'nome'    => 'João da Silva',
        'unidade' => 'Unidade Central',
    ]);

    $sut->assertJsonFragment([
        'tipo_logradouro' => $address->tipo_logradouro,
        'logradouro'      => $address->logradouro,
        'numero'          => $address->numero,
        'bairro'          => $address->bairro,
        'cidade'          => $city->nome,
    ]);

    $sut->assertJsonStructure([
        'data',
        'pagination' => [
            'total',
            'per_page',
            'current_page',
            'last_page',
            'from',
            'to',
        ],
    ]);
});

it('should return only matching functional addresses (4 out of 15)', function (): void {
    $city    = City::factory()->create(['nome' => 'CityName']);
    $address = Address::factory()->create([
        'tipo_logradouro' => 'Rua',
        'logradouro'      => 'Das Flores',
        'numero'          => 123,
        'bairro'          => 'Centro',
        'cid_id'          => $city->id,
    ]);
    $unit = Unit::factory()->create([
        'nome'  => 'Unidade Central',
        'sigla' => 'UC',
    ]);
    AddressUnit::factory()->create([
        'end_id'  => $address->id,
        'unid_id' => $unit->id,
    ]);

    Person::factory()->count(11)->create()->each(function ($person) use ($unit) {
        Assignment::factory()->create([
            'pes_id'  => $person->id,
            'unid_id' => $unit->id,
        ]);
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    Person::factory()->count(4)->create(['nome' => 'João da Silva'])->each(function ($person) use ($unit) {
        Assignment::factory()->create([
            'pes_id'  => $person->id,
            'unid_id' => $unit->id,
        ]);
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.functional-address.search', [
        'nome'     => 'João',
        'per_page' => 10,
    ]));

    $sut->assertOk();
    expect($sut->json('data'))->toHaveCount(4)
        ->and($sut->json('pagination.total'))->toBe(4);
});

it('should return paginated results with per_page set to 5', function (): void {
    $unit    = Unit::factory()->create();
    $city    = City::factory()->create();
    $address = Address::factory()->create(['cid_id' => $city->id]);
    AddressUnit::factory()->create(['end_id' => $address->id, 'unid_id' => $unit->id]);

    Person::factory()->count(10)->create([
        'nome' => 'João Teste',
    ])->each(function ($person) use ($unit) {
        Assignment::factory()->create([
            'pes_id'  => $person->id,
            'unid_id' => $unit->id,
        ]);

        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.functional-address.search', [
        'nome'     => 'João',
        'per_page' => 5,
    ]));

    $sut->assertOk();

    expect($sut->json('data'))->toHaveCount(5)
        ->and($sut->json('pagination.per_page'))->toBe(5)
        ->and($sut->json('pagination.total'))->toBe(10);
});

it('should return correct second page when paginating results', function (): void {
    $unit    = Unit::factory()->create();
    $city    = City::factory()->create();
    $address = Address::factory()->create(['cid_id' => $city->id]);
    AddressUnit::factory()->create([
        'end_id'  => $address->id,
        'unid_id' => $unit->id,
    ]);

    Person::factory()->count(8)->create(['nome' => 'João Teste'])->each(function ($person) use ($unit) {
        Assignment::factory()->create([
            'pes_id'  => $person->id,
            'unid_id' => $unit->id,
        ]);
        PermanentServants::factory()->create(['pes_id' => $person->id]);
    });

    $sut = getJson(route('api.functional-address.search', [
        'nome'     => 'João',
        'per_page' => 5,
        'page'     => 2,
    ]));

    $sut->assertOk();
    expect($sut->json('data'))->toHaveCount(3)
        ->and($sut->json('pagination.current_page'))->toBe(2); // 8 total: 5 na página 1 e 3 na página 2
});
