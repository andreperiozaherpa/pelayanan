<?php

namespace Database\Seeders;

use App\Models\Gerai;
use App\Models\Opd;
use Illuminate\Database\Seeder;

class MppGeraiSeeder extends Seeder
{
    public function run(): void
    {
        $gerais = [
            ['code' => 'A', 'opd_code' => '11', 'name' => 'Gerai Perekaman - Dinas Kependudukan & Pencatatan Sipil'],
            ['code' => 'AB', 'opd_code' => '11', 'name' => 'Gerai Pencetakan - Dinas Kependudukan & Pencatatan Sipil'],
            ['code' => 'B', 'opd_code' => '17', 'name' => 'Gerai Dinas Penanaman Modal & PTSP'],
            ['code' => 'C', 'opd_code' => '09', 'name' => 'Gerai Dinas Sosial'],
            ['code' => 'D', 'opd_code' => '05', 'name' => 'Gerai Dinas Kesehatan'],
            ['code' => 'E', 'opd_code' => '29', 'name' => 'Gerai Badan Penyelenggara Jaminan Produk Halal'],
            ['code' => 'F', 'opd_code' => '30', 'name' => 'Gerai Kantor Pelayanan Pajak Pratama Kotabumi'],
            ['code' => 'G', 'opd_code' => '31', 'name' => 'Gerai BPJS Ketenagakerjaan Lampung Tengah'],
            ['code' => 'H', 'opd_code' => '32', 'name' => 'Gerai Kejaksaan Negeri Tulang Bawang Barat'],
            ['code' => 'I', 'opd_code' => '33', 'name' => 'Gerai Kementerian Agama RI Kab. Tubaba'],
            ['code' => 'J', 'opd_code' => '34', 'name' => 'Gerai Kantor Imigrasi Kelas III Non TPI Kotabumi'],
            ['code' => 'K', 'opd_code' => '35', 'name' => 'Gerai Kantor Layanan BNNP Lampung Kab. Tubaba'],
            ['code' => 'L', 'opd_code' => '36', 'name' => 'Gerai Kepolisian Resor (POLRES) Tulang Bawang Barat'],
            ['code' => 'M', 'opd_code' => '37', 'name' => 'Gerai Pengadilan Agama Tulang Bawang Tengah'],
            ['code' => 'N', 'opd_code' => '38', 'name' => 'Gerai Loka Pengawas Obat dan Makanan (BPOM)'],
            ['code' => 'O', 'opd_code' => '39', 'name' => 'Gerai Kantor Pertanahan Kab. Tulang Bawang Barat'],
            ['code' => 'P', 'opd_code' => '40', 'name' => 'Gerai Bank Pembangunan Daerah Lampung Cabang Panaragan'],
            ['code' => 'Q', 'opd_code' => '10', 'name' => 'Gerai Dinas Pemberdayaan Perempuan & Perlindungan Anak'],
            ['code' => 'R', 'opd_code' => '41', 'name' => 'Gerai Dinas Tanaman Pangan, Hortikultura & Perkebunan'],
            ['code' => 'S', 'opd_code' => '42', 'name' => 'Gerai Dinas Peternakan & Kesehatan Hewan'],
            ['code' => 'T', 'opd_code' => '43', 'name' => 'Gerai Badan Pendapatan Daerah (BAPENDA)'],
            ['code' => 'U', 'opd_code' => '21', 'name' => 'Gerai Dinas Lingkungan Hidup'],
            ['code' => 'V', 'opd_code' => '04', 'name' => 'Gerai Dinas Pendidikan & Kebudayaan'],
            ['code' => 'W', 'opd_code' => '44', 'name' => 'Gerai BPJS Kesehatan Cabang Metro'],
            ['code' => 'X', 'opd_code' => '45', 'name' => 'Gerai Dinas Tenaga Kerja & Transmigrasi'],
        ];

        $opds = Opd::whereIn('code', array_column($gerais, 'opd_code'))->get()->keyBy('code');

        foreach ($gerais as $gerai) {
            $opd = $opds->get($gerai['opd_code']);

            if (! $opd) {
                $this->command->warn("Opd dengan kode {$gerai['opd_code']} tidak ditemukan untuk gerai {$gerai['code']}.");

                continue;
            }

            Gerai::updateOrCreate(['code' => $gerai['code']], [
                'opd_id' => $opd->id,
                'name' => $gerai['name'],
                'is_active' => true,
            ]);
        }
    }
}
