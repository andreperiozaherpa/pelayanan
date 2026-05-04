<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitizenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isAuthorized = $user && (
            $user->isSuperAdmin() ||
            ($user->isOperatorDesa() && $user->desa_id === $this->desa_id)
        );

        return [
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama_lengkap,
            'tgl_lahir' => $this->tgl_lahir->format('Y-m-d'),
            'alamat_desa' => $isAuthorized ? $this->alamat_desa : $this->maskAddress($this->alamat_desa),
            'kontak' => $isAuthorized ? $this->kontak : $this->maskPhone($this->kontak),
            'desa_id' => $this->desa_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Mask address: 3 chars + space + stars
     * Example: "Jl. Merdeka No. 123" -> "Jl. ****************"
     */
    private function maskAddress(?string $address): string
    {
        if (! $address) {
            return '**********';
        }

        $prefix = substr($address, 0, 3);
        $remainingLength = strlen($address) - 3;
        $stars = str_repeat('*', $remainingLength);

        // ✅ Dengan spasi untuk alamat
        return $prefix.' '.$stars;
    }

    /**
     * Mask phone: 3 digits + stars (no space)
     * Example: "08123456789" -> "081*******"
     */
    private function maskPhone(?string $phone): string
    {
        if (! $phone) {
            return '**********';
        }

        $prefix = substr($phone, 0, 3);
        $remainingLength = strlen($phone) - 3;
        $stars = str_repeat('*', $remainingLength);

        // ✅ Tanpa spasi untuk nomor telepon
        return $prefix.$stars;
    }
}
