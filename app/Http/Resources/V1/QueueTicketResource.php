<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $gerai = $this->counter;

        return [
            'id' => $this->id,
            'nomor_antrian' => $this->number,
            'service_id' => $this->service_id,
            'service_name' => $this->service?->name,
            'counter_tujuan_id' => $this->counter_id,
            'counter_tujuan' => $this->counter_name,
            'status' => $this->status,
            'fo_petugas_id' => $this->fo_petugas_id,
            'gerai_petugas_id' => $this->gerai_petugas_id,
            'catatan' => $this->notes,
            'alasan_reject' => $this->alasan_reject,
            'tgl_ambil' => $this->created_at,
            'called_at' => $this->called_at,
            'done_at' => $this->done_at,
            'fo_called_at' => $this->fo_called_at,
            'fo_finished_at' => $this->fo_finished_at,
            'gerai_called_at' => $this->gerai_called_at,
            'durasi_fo' => $this->durasi_fo,
            'durasi_fo_detik' => $this->durasi_fo_detik,
            'durasi_gerai' => $this->durasi_gerai,
            'durasi_gerai_detik' => $this->durasi_gerai_detik,
            'durasi_total' => $this->durasi_total,
            'durasi_total_detik' => $this->durasi_total_detik,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'service' => $this->when($this->relationLoaded('service'), [
                'id' => $this->service?->id,
                'kode' => $this->service?->gerai?->code ?? $this->service?->opd?->gerais?->first()?->code,
                'nama' => $this->service?->name,
                'opd_id' => $this->service?->opd_id,
            ]),
            'gerai' => $this->when($this->relationLoaded('counter') && $gerai, [
                'id' => $gerai?->id,
                'kode' => $gerai?->code,
                'nama' => $gerai?->name,
                'lokasi' => $gerai?->location,
            ]),
        ];
    }
}
