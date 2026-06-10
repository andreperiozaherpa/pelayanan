<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    /**
     * Daftar OPD resmi Kabupaten Tulang Bawang Barat.
     *
     * Sumber: Struktur Organisasi Perangkat Daerah Kab. Tulang Bawang Barat
     */
    public function run(): void
    {
        $opds = [
            ['code' => '01', 'name' => 'Sekretariat Daerah', 'description' => 'Unsur pembantu kepala daerah dalam penyusunan kebijakan dan pengoordinasian perangkat daerah.'],
            ['code' => '02', 'name' => 'Sekretariat DPRD', 'description' => 'Unsur pelayanan administrasi dan pemberian dukungan terhadap tugas dan fungsi DPRD.'],
            ['code' => '03', 'name' => 'Inspektorat Daerah', 'description' => 'Unsur pengawas penyelenggaraan pemerintahan daerah.'],
            ['code' => '04', 'name' => 'Dinas Pendidikan dan Kebudayaan', 'description' => 'Urusan pemerintahan bidang pendidikan dan kebudayaan.'],
            ['code' => '05', 'name' => 'Dinas Kesehatan', 'description' => 'Urusan pemerintahan bidang kesehatan.'],
            ['code' => '06', 'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'description' => 'Urusan pemerintahan bidang pekerjaan umum dan penataan ruang.'],
            ['code' => '07', 'name' => 'Dinas Perumahan dan Kawasan Permukiman', 'description' => 'Urusan pemerintahan bidang perumahan rakyat dan kawasan permukiman.'],
            ['code' => '08', 'name' => 'Satuan Polisi Pamong Praja', 'description' => 'Penegakan peraturan daerah, ketertiban umum dan ketentraman masyarakat.'],
            ['code' => '09', 'name' => 'Dinas Sosial', 'description' => 'Urusan pemerintahan bidang sosial.'],
            ['code' => '10', 'name' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', 'description' => 'Urusan pemerintahan bidang pemberdayaan perempuan dan perlindungan anak.'],
            ['code' => '11', 'name' => 'Dinas Kependudukan dan Pencatatan Sipil', 'description' => 'Urusan pemerintahan bidang administrasi kependudukan dan pencatatan sipil.'],
            ['code' => '12', 'name' => 'Dinas Pemberdayaan Masyarakat dan Pemerintahan Kampung', 'description' => 'Urusan pemerintahan bidang pemberdayaan masyarakat dan kampung.'],
            ['code' => '13', 'name' => 'Dinas Pengendalian Penduduk dan Keluarga Berencana', 'description' => 'Urusan pemerintahan bidang pengendalian penduduk dan keluarga berencana.'],
            ['code' => '14', 'name' => 'Dinas Perhubungan', 'description' => 'Urusan pemerintahan bidang perhubungan.'],
            ['code' => '15', 'name' => 'Dinas Komunikasi dan Informatika', 'description' => 'Urusan pemerintahan bidang komunikasi dan informatika.'],
            ['code' => '16', 'name' => 'Dinas Koperasi, Usaha Kecil dan Menengah', 'description' => 'Urusan pemerintahan bidang koperasi, usaha kecil dan menengah.'],
            ['code' => '17', 'name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu', 'description' => 'Urusan pemerintahan bidang penanaman modal dan pelayanan terpadu.'],
            ['code' => '18', 'name' => 'Dinas Kepemudaan dan Olahraga', 'description' => 'Urusan pemerintahan bidang kepemudaan dan olahraga.'],
            ['code' => '19', 'name' => 'Dinas Perpustakaan dan Kearsipan', 'description' => 'Urusan pemerintahan bidang perpustakaan dan kearsipan.'],
            ['code' => '20', 'name' => 'Dinas Ketahanan Pangan', 'description' => 'Urusan pemerintahan bidang ketahanan pangan.'],
            ['code' => '21', 'name' => 'Dinas Lingkungan Hidup', 'description' => 'Urusan pemerintahan bidang lingkungan hidup.'],
            ['code' => '22', 'name' => 'Dinas Pertanian', 'description' => 'Urusan pemerintahan bidang pertanian.'],
            ['code' => '23', 'name' => 'Dinas Perikanan', 'description' => 'Urusan pemerintahan bidang kelautan dan perikanan.'],
            ['code' => '24', 'name' => 'Dinas Perdagangan dan Perindustrian', 'description' => 'Urusan pemerintahan bidang perdagangan dan perindustrian.'],
            ['code' => '25', 'name' => 'Badan Perencanaan Pembangunan Daerah', 'description' => 'Fungsi penunjang urusan pemerintahan bidang perencanaan pembangunan.'],
            ['code' => '26', 'name' => 'Badan Pengelola Keuangan dan Aset Daerah', 'description' => 'Fungsi penunjang urusan pemerintahan bidang keuangan dan aset daerah.'],
            ['code' => '27', 'name' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia', 'description' => 'Fungsi penunjang urusan pemerintahan bidang kepegawaian.'],
            ['code' => '28', 'name' => 'Badan Penanggulangan Bencana Daerah', 'description' => 'Penanggulangan bencana di lingkup Kabupaten Tulang Bawang Barat.'],
        ];

        foreach ($opds as $opd) {
            Opd::updateOrCreate(['code' => $opd['code']], $opd);
        }
    }
}
