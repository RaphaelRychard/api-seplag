<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Models\Person;
use App\Models\TemporaryServants;
use Illuminate\Support\Facades\DB;
use Throwable;

class TemporaryServantServices
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

            return TemporaryServants::create([
                'pes_id'        => $person->id,
                'data_admissao' => $data['data_admissao'],
                'data_demissao' => $data['data_demissao'],
            ]);
        });
    }

    /**
     * @throws Throwable
     */
    public function update(TemporaryServants $temporaryServants, array $data)
    {
        return DB::transaction(function () use ($temporaryServants, $data): TemporaryServants {
            $temporaryServants->person->update([
                'nome'            => $data['nome'],
                'data_nascimento' => $data['data_nascimento'],
                'sexo'            => $data['sexo'],
                'mae'             => $data['mae'],
                'pai'             => $data['pai'],
            ]);

            $temporaryServants->update([
                'data_admissao' => $data['data_admissao'],
                'data_demissao' => $data['data_demissao'],
            ]);

            return $temporaryServants;
        });
    }
}
