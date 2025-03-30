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
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'nome'            => $this->nome,
            'data_nascimento' => $this->data_nascimento,
            'sexo'            => $this->sexo,
            'mae'             => $this->mae,
            'pai'             => $this->pai,
        ];
    }
}
