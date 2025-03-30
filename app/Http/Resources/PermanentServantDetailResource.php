<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PermanentServantDetailResource extends JsonResource
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
            'id'         => $this->id,
            'pes_id'     => $this->pes_id,
            'nome'       => $this->person->nome,
            'idade'      => Carbon::parse($this->person->data_nascimento)->age,
            'fotografia' => $this->getFotoUrl(),
            'unidade'    => optional($this->person->assignment)->unit,
            'lotacao'    => optional($this->person)->assignment,
        ];
    }

    /**
     * Obtém a URL temporária da fotografia.
     */
    private function getFotoUrl(): ?string
    {
        $photo = $this->person->personsPhoto;

        if ($photo && ! empty($photo->hash)) {
            $filePath = "uploads/{$photo->hash}"; // Ajuste a extensão conforme necessário

            return Storage::disk('minio')
                ->temporaryUrl($filePath, now()->addMinutes(5));
        }

        return null;
    }
}
