<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $counter = $this->activeCounter();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->name,
            'role' => $this->role->slug ?? null,
            'role_name' => $this->role->name ?? null,
            'role_slug' => $this->role->slug ?? null,
            'counter_id' => $counter?->id,
            'counter_nama' => $counter?->name,
            'instansi' => $counter?->gerai?->opd ? [
                'id' => $counter->gerai->opd->id,
                'kode' => $counter->gerai->opd->code,
                'nama' => $counter->gerai->opd->name,
                'gerai' => $counter->gerai->code,
            ] : null,
            'desa_id' => $this->desa_id,
        ];
    }
}
