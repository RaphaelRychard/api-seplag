<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FunctionalAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        $addressUnit = optional($this->assignment->unit->addressUnit ?? null);
        $address     = optional($addressUnit->address ?? null);

        return [
            'nome'     => $this->nome,
            'unidade'  => $this->assignment->unit->nome ?? null,
            'endereco' => [
                'tipo_logradouro' => $address->tipo_logradouro,
                'logradouro'      => $address->logradouro,
                'numero'          => $address->numero,
                'bairro'          => $address->bairro,
                'cidade'          => $address->city->nome ?? null,
            ],
        ];
    }
}
