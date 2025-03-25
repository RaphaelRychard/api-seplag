<?php

declare(strict_types = 1);

namespace App\Http\Mappers;

class PersonMapper
{
    public static function mapper(array $data): array
    {
        return [
            'pes_nome'            => $data['nome'],
            'pes_data_nascimento' => $data['data_nascimento'],
            'pes_sexo'            => $data['sexo'],
            'pes_mae'             => $data['mae'],
            'pes_pai'             => $data['pai'],
        ];
    }
}
