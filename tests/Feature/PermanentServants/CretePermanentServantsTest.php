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

    $assignment = Assignment::factory()->create([
        'pes_id'       => $person->id,
        'unid_id'      => $unit->id,
        'data_lotacao' => '2020-01-01',
        'data_remocao' => null,
        'portaria'     => '123/2020',
    ]);

    PermanentServants::factory()->create([
        'pes_id' => $person->id,
    ]);

    $sut = getJson(route('api.functional-address.search', ['nome' => 'João']));

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
});
