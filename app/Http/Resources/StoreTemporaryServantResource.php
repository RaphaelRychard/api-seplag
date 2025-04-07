<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class StoreTemporaryServantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'pes_id'        => $this->pes_id,
            'data_admissao' => $this->data_admissao,
            'data_demissao' => $this->data_demissao,
        ];
    }
}
