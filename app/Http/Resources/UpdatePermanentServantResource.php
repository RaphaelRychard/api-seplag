<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdatePermanentServantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nome'            => $this->person->nome,
            'data_nascimento' => $this->person->data_nascimento,
            'sexo'            => $this->person->sexo,
            'mae'             => $this->person->mae,
            'pai'             => $this->person->pai,
            'se_matricula'    => $this->se_matricula,
        ];
    }
}
