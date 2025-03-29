<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Models\PermanentServants;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class CreatePermanentServantService
{
    /**
     * Cria um servidor permanente com seus dados pessoais.
     *
     * @param array $data
     * @return PermanentServants
     */
    public function create(array $data): PermanentServants
    {
        return DB::transaction(function () use ($data) {
            $person = $this->createPerson($data);

            return $this->createPermanentServant($data, $person);
        });
    }

    /**
     * Cria uma nova pessoa.
     *
     * @param array $data
     * @return Person
     */
    private function createPerson(array $data): Person
    {
        return Person::create([
            'nome'            => $data['nome'],
            'data_nascimento' => $data['data_nascimento'],
            'sexo'            => $data['sexo'],
            'mae'             => $data['mae'],
            'pai'             => $data['pai'],
        ]);
    }

    /**
     * Cria um servidor permanente e o vincula a uma pessoa.
     *
     * @param array $data
     * @param Person $person
     * @return PermanentServants
     */
    private function createPermanentServant(array $data, Person $person): PermanentServants
    {
        return PermanentServants::create([
            'pes_id'       => $person->id,
            'se_matricula' => $data['se_matricula'],
        ]);
    }
}
