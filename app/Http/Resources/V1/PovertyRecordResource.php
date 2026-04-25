<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PovertyRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isAuthorized = $user && (
            $user->role->slug === 'superadmin' || 
            ($user->role->slug === 'operatordesa' && $user->desa_id === $this->citizen->desa_id)
        );

        return [
            'id' => $this->id,
            'citizen_nik' => $this->citizen_nik,
            'status' => $this->status,
            'income_range' => $isAuthorized ? $this->income_range : '**********',
            'valid_from' => $this->valid_from->format('Y-m-d'),
            'valid_until' => $this->valid_until->format('Y-m-d'),
            'source' => $this->source,
            'verified_by' => new UserResource($this->whenLoaded('verifiedBy')),
            'citizen' => new CitizenResource($this->whenLoaded('citizen')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
