<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Models\PermanentServants;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Throwable;

class PermanentServantServices
{
    /**
     * @throws Throwable
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $person = Person::create([
                'nome'            => $data['nome'],
                'data_nascimento' => $data['data_nascimento'],
                'sexo'            => $data['sexo'],
                'mae'             => $data['mae'],
                'pai'             => $data['pai'],
            ]);

            return PermanentServants::create([
                'se_matricula' => $data['se_matricula'],
                'pes_id'       => $person->id,
            ]);
        });
    }

    /**
     * @throws Throwable
     */
    public function update(PermanentServants $permanentServant, array $data)
    {
        return DB::transaction(function () use ($permanentServant, $data): PermanentServants {
            $permanentServant->person->update([
                'nome'            => $data['nome'],
                'data_nascimento' => $data['data_nascimento'],
                'sexo'            => $data['sexo'],
                'mae'             => $data['mae'],
                'pai'             => $data['pai'],
            ]);

            $permanentServant->update([
                'se_matricula' => $data['se_matricula'],
            ]);

            return $permanentServant;
        });
    }
}
