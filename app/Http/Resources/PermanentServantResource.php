<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PermanentServantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photo   = $this->person->personsPhoto;
        $fotoUrl = null;

        if ($photo && ! empty($photo->path)) {
            $fotoUrl = Storage::disk('minio')
                ->temporaryUrl($photo->path, now()->addMinutes(5));
        }

        return [
            'id'              => $this->id,
            'pes_id'          => $this->person->pes_id,
            'nome'            => $this->person->nome,
            'idade'           => Carbon::parse($this->person->data_nascimento)->age,
            'fotografia'      => $fotoUrl,
            'unidade_lotacao' => $this->getUnitOfLotacao(),
        ];
    }
}
