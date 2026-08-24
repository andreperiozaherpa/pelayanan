<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $gerai = $this->gerai ?? $this->opd?->gerais?->first();

        return [
            'id' => $this->id,
            'nama' => $this->name,
            'deskripsi' => $this->description,
            'fields' => $this->fields ?? [],
            'logo' => $this->logo ? asset('storage/'.$this->logo) : null,
            'instansi' => $this->whenLoaded('opd', fn () => [
                'id' => $this->opd?->id,
                'kode' => $this->opd?->code,
                'nama' => $this->opd?->name,
                'gerai' => [
                    'kode' => $gerai?->code,
                    'nama' => $gerai?->name,
                ],
            ]),
        ];
    }
}
