<?php

namespace Database\Seeders;

use App\Models\Anjungan;
use App\Models\MppService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MppServiceSeeder extends Seeder
{
    public function run(): void
    {
        $servicesByAnjungan = [
            'ANJ-001' => [
                ['name' => 'KTP Elektronik', 'fields' => [
                    ['label' => 'NIK', 'type' => 'text', 'required' => true],
                    ['label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                ]],
                ['name' => 'Kartu Keluarga', 'fields' => [
                    ['label' => 'No. KK', 'type' => 'text', 'required' => true],
                    ['label' => 'Kepala Keluarga', 'type' => 'text', 'required' => true],
                ]],
                ['name' => 'Akte Kelahiran', 'fields' => [
                    ['label' => 'Nama Anak', 'type' => 'text', 'required' => true],
                    ['label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
                    ['label' => 'Tanggal Lahir', 'type' => 'text', 'required' => true],
                    ['label' => 'Dokumen Pendukung', 'type' => 'file', 'required' => false],
                ]],
            ],
            'ANJ-002' => [
                ['name' => 'Izin Usaha Mikro Kecil', 'fields' => [
                    ['label' => 'Nama Usaha', 'type' => 'text', 'required' => true],
                    ['label' => 'Jenis Usaha', 'type' => 'select', 'required' => true, 'options' => ['Kuliner', 'Fashion', 'Kerajinan', 'Jasa']],
                ]],
                ['name' => 'Sertifikasi Halal', 'fields' => [
                    ['label' => 'Nama Produk', 'type' => 'text', 'required' => true],
                    ['label' => 'Bahan Baku', 'type' => 'textarea', 'required' => true],
                ]],
            ],
            'ANJ-003' => [
                ['name' => 'Bantuan Sosial Tunai', 'fields' => [
                    ['label' => 'NIK', 'type' => 'text', 'required' => true],
                    ['label' => 'Jenis Bantuan', 'type' => 'select', 'required' => true, 'options' => ['BST', 'PKH', 'BNPT']],
                ]],
                ['name' => 'Kartu Indonesia Sehat', 'fields' => [
                    ['label' => 'NIK', 'type' => 'text', 'required' => true],
                    ['label' => 'Faskes Tujuan', 'type' => 'text', 'required' => true],
                ]],
            ],
            'ANJ-004' => [
                ['name' => 'Antrean Puskesmas', 'fields' => [
                    ['label' => 'NIK', 'type' => 'text', 'required' => true],
                    ['label' => 'Poli Tujuan', 'type' => 'select', 'required' => true, 'options' => ['Umum', 'Gigi', 'KIA', 'Lansia']],
                ]],
                ['name' => 'Rujukan BPJS', 'fields' => [
                    ['label' => 'No. BPJS', 'type' => 'text', 'required' => true],
                    ['label' => 'Diagnosa Awal', 'type' => 'textarea', 'required' => true],
                ]],
                ['name' => 'Imunisasi Anak', 'fields' => [
                    ['label' => 'Nama Anak', 'type' => 'text', 'required' => true],
                    ['label' => 'Jenis Imunisasi', 'type' => 'select', 'required' => true, 'options' => ['BCG', 'DPT', 'Polio', 'Campak', 'Hepatitis B']],
                ]],
            ],
            'ANJ-005' => [
                ['name' => 'Izin Mendirikan Bangunan', 'fields' => [
                    ['label' => 'Luas Bangunan', 'type' => 'number', 'required' => true],
                    ['label' => 'Fungsi Bangunan', 'type' => 'select', 'required' => true, 'options' => ['Hunian', 'Komersial', 'Campuran']],
                ]],
                ['name' => 'Surat Keterangan Rencana Kabupaten', 'fields' => [
                    ['label' => 'Lokasi Tanah', 'type' => 'textarea', 'required' => true],
                    ['label' => 'Luas Tanah', 'type' => 'number', 'required' => true],
                ]],
            ],
        ];

        $anjungans = Anjungan::whereIn('code', array_keys($servicesByAnjungan))->get()->keyBy('code');

        foreach ($servicesByAnjungan as $code => $services) {
            $anjungan = $anjungans->get($code);
            if (! $anjungan) {
                continue;
            }

            foreach ($services as $svc) {
                $fields = [];
                foreach ($svc['fields'] as $f) {
                    $fieldName = Str::slug($f['label'], '_');
                    $fields[] = [
                        'id' => uniqid('field_'),
                        'name' => $fieldName,
                        'label' => $f['label'],
                        'type' => $f['type'],
                        'required' => $f['required'] ?? false,
                        'options' => ($f['type'] === 'select' && ! empty($f['options'])) ? $f['options'] : [],
                    ];
                }

                MppService::create([
                    'name' => $svc['name'],
                    'slug' => Str::slug($svc['name']),
                    'description' => 'Pelayanan '.$svc['name'].' melalui '.$anjungan->name,
                    'anjungan_id' => $anjungan->id,
                    'fields' => $fields,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Seeded '.MppService::count().' MPP services.');
    }
}
