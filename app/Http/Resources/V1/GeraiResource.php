<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeraiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode' => $this->code,
            'nama' => $this->name,
            'lokasi' => $this->location,
            'opd_id' => $this->gerai?->opd_id,
        ];
    }
}
