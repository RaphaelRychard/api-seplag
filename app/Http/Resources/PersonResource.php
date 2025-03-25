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
            'id'              => $this->pes_id,
            'nome'            => $this->pes_nome,
            'data_nascimento' => $this->pes_data_nascimento,
            'sexo'            => $this->pes_sexo,
            'mae'             => $this->pes_mae,
            'pai'             => $this->pes_pai,
        ];
    }
}
