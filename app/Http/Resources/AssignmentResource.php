<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'id'           => $this->id,
            'pes_id'       => $this->pes_id,
            'unid_id'      => $this->unid_id,
            'data_lotacao' => $this->data_lotacao,
            'data_remocao' => $this->data_remocao,
            'portaria'     => $this->portaria,
        ];
    }
}
