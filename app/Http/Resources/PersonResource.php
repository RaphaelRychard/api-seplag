<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'pes_id'              => $this->pes_id,
            'pes_nome'            => $this->pes_nome,
            'pes_data_nascimento' => $this->pes_data_nascimento,
            'pes_sexo'            => $this->pes_sexo,
            'pes_mae'             => $this->pes_mae,
            'pes_pai'             => $this->pes_pai,
        ];
    }
}
