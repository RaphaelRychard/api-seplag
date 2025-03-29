<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermanentServantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'pes_id'          => $this->person->id,
            'nome'            => $this->person->nome,
            'idade'           => Carbon::parse($this->person->data_nascimento)->age,
            'fotografia'      => $this->person->personsPhoto,
            'unidade_lotacao' => $this->getUnitOfLotacao(),
        ];
    }
}
