<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if it's a VillageLeader or DistrictLeader
        $isVillage = isset($this->village_id);

        return (array) [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'nip' => $this->nip,
            'rank' => $this->rank,
            'type' => $isVillage ? 'Village' : 'District',
            'unit_type' => $isVillage ? 'Kepala Desa' : 'Camat',
            'unit_name' => $isVillage ? ($this->village->name ?? '-') : ($this->district->name ?? '-'),
            'certificate' => $this->user->certificates->first() ?? null,
            'user' => $this->user,
        ];
    }
}
