<?php

namespace App\Services;

use Illuminate\Support\Collection;

class SkmService
{
    /**
     * Sembilan unsur SKM sesuai Permen PAN-RB No. 14 Tahun 2017 Lampiran I.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function unsur(): array
    {
        return [
            ['key' => 'u1', 'kode' => 'U1', 'nama' => 'Persyaratan', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya?', 'opsi' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai']],
            ['key' => 'u2', 'kode' => 'U2', 'nama' => 'Sistem, Mekanisme, dan Prosedur', 'pertanyaan' => 'Bagaimana pemahaman Saudara tentang kemudahan prosedur pelayanan di unit ini?', 'opsi' => ['Tidak mudah', 'Kurang mudah', 'Mudah', 'Sangat mudah']],
            ['key' => 'u3', 'kode' => 'U3', 'nama' => 'Waktu Penyelesaian', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang kecepatan waktu dalam memberikan pelayanan?', 'opsi' => ['Tidak cepat', 'Kurang cepat', 'Cepat', 'Sangat cepat']],
            ['key' => 'u4', 'kode' => 'U4', 'nama' => 'Biaya/Tarif', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang kewajaran biaya/tarif dalam pelayanan?', 'opsi' => ['Sangat mahal', 'Cukup mahal', 'Murah', 'Gratis']],
            ['key' => 'u5', 'kode' => 'U5', 'nama' => 'Produk Spesifikasi Jenis Pelayanan', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang kesesuaian produk pelayanan antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan?', 'opsi' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai']],
            ['key' => 'u6', 'kode' => 'U6', 'nama' => 'Kompetensi Pelaksana', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam pelayanan?', 'opsi' => ['Tidak kompeten', 'Kurang kompeten', 'Kompeten', 'Sangat kompeten']],
            ['key' => 'u7', 'kode' => 'U7', 'nama' => 'Perilaku Pelaksana', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang perilaku petugas dalam pelayanan terkait kesopanan dan keramahan?', 'opsi' => ['Tidak sopan dan ramah', 'Kurang sopan dan ramah', 'Sopan dan ramah', 'Sangat sopan dan ramah']],
            ['key' => 'u8', 'kode' => 'U8', 'nama' => 'Penanganan Pengaduan, Saran dan Masukan', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan?', 'opsi' => ['Tidak ada', 'Ada tetapi tidak berfungsi', 'Berfungsi kurang maksimal', 'Dikelola dengan baik']],
            ['key' => 'u9', 'kode' => 'U9', 'nama' => 'Sarana dan Prasarana', 'pertanyaan' => 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana?', 'opsi' => ['Buruk', 'Cukup', 'Baik', 'Sangat baik']],
        ];
    }

    /**
     * Hitung rekap SKM dari sekumpulan respons.
     *
     * @return array<string, mixed>
     */
    public function rekap(Collection $responses): array
    {
        $count = $responses->count();
        $unsurRekap = [];
        $totalNilai = 0.0;

        foreach ($this->unsur() as $u) {
            $nrr = $count > 0 ? round((float) $responses->avg($u['key']), 2) : 0.0;

            $unsurRekap[$u['key']] = [
                'kode' => $u['kode'],
                'nama' => $u['nama'],
                'nrr' => $nrr,
                'ikm' => round($nrr * 25, 2),
            ];

            $totalNilai += $nrr;
        }

        $ikm = $count > 0 ? round(($totalNilai / 9) * 25, 2) : 0.0;

        return [
            'total_responden' => $count,
            'unsur' => $unsurRekap,
            'ikm' => $ikm,
            'mutu' => $this->mutu($ikm),
            'prioritas' => collect($unsurRekap)->sortBy('ikm')->values(),
        ];
    }

    /**
     * Kategori mutu pelayanan (Tabel II Permen PAN-RB No. 14 Tahun 2017).
     *
     * @return array<string, string>
     */
    public function mutu(float $ikm): array
    {
        return match (true) {
            $ikm >= 88.31 => ['nilai' => 'A', 'kinerja' => 'Sangat baik', 'kepuasan' => 'Sangat puas'],
            $ikm >= 76.61 => ['nilai' => 'B', 'kinerja' => 'Baik', 'kepuasan' => 'Puas'],
            $ikm >= 65.00 => ['nilai' => 'C', 'kinerja' => 'Kurang baik', 'kepuasan' => 'Kurang puas'],
            default => ['nilai' => 'D', 'kinerja' => 'Tidak baik', 'kepuasan' => 'Tidak puas'],
        };
    }
}
