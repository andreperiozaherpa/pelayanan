<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RBACSeeder extends Seeder
{
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            [
                'name' => 'Verifikasi Status Layanan',
                'slug' => 'service.verify',
                'description' => 'Akses untuk memverifikasi status data dan dokumen warga yang mengajukan layanan.',
            ],
            [
                'name' => 'Cetak Bukti Verifikasi',
                'slug' => 'service.print_proof',
                'description' => 'Akses untuk mencetak bukti verifikasi layanan sebagai dokumen resmi.',
            ],
            [
                'name' => 'Lapor Penggunaan Layanan',
                'slug' => 'service.report',
                'description' => 'Akses untuk melaporkan penggunaan layanan atau mengajukan permintaan verifikasi warga.',
            ],
            [
                'name' => 'Kelola Data Warga',
                'slug' => 'citizens.manage',
                'description' => 'Akses untuk mengelola data kependudukan warga termasuk tambah, ubah, dan hapus.',
            ],
            [
                'name' => 'Lihat Log Audit',
                'slug' => 'audit.view',
                'description' => 'Akses untuk melihat log audit aktivitas sistem termasuk riwayat perubahan data.',
            ],
            [
                'name' => 'Kelola Sistem',
                'slug' => 'system.manage',
                'description' => 'Akses penuh untuk mengelola konfigurasi dan pengaturan sistem aplikasi.',
            ],
            [
                'name' => 'Kelola Pengguna',
                'slug' => 'users.manage',
                'description' => 'Akses untuk mengelola akun pengguna termasuk tambah, ubah, dan nonaktifkan.',
            ],
            [
                'name' => 'Kelola Role & Hak Akses',
                'slug' => 'roles.manage',
                'description' => 'Akses untuk mengelola role dan hak akses pengguna dalam sistem.',
            ],
            [
                'name' => 'Kelola Data Desa',
                'slug' => 'villages.manage',
                'description' => 'Akses untuk mengelola data desa termasuk tambah, ubah, dan hapus.',
            ],
            [
                'name' => 'Kelola Data Kecamatan',
                'slug' => 'districts.manage',
                'description' => 'Akses untuk mengelola data kecamatan termasuk tambah, ubah, dan hapus.',
            ],
            [
                'name' => 'Kelola Data OPD',
                'slug' => 'opds.manage',
                'description' => 'Akses untuk mengelola data Organisasi Perangkat Daerah termasuk tambah, ubah, dan hapus.',
            ],
            [
                'name' => 'Kelola Data Peta SIBERUGO',
                'slug' => 'maps.manage',
                'description' => 'Akses untuk mengelola data peta spasial, region, zona, dan titik lokasi SIBERUGO.',
            ],
            [
                'name' => 'Ekspor Laporan',
                'slug' => 'reports.export',
                'description' => 'Akses untuk mengekspor laporan data dalam format yang tersedia (Excel, PDF, dll).',
            ],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Define Roles & Assign Permissions
        $roles = [
            'SuperAdmin' => ['service.verify', 'service.print_proof', 'service.report', 'citizens.manage', 'audit.view', 'system.manage', 'users.manage', 'roles.manage', 'villages.manage', 'districts.manage', 'opds.manage', 'maps.manage', 'reports.export'],
            'OperatorDesa' => ['service.verify'],
            'OperatorOpd' => ['service.verify', 'service.print_proof', 'service.report'],
            'PetugasFrontOffice' => ['service.verify', 'service.print_proof', 'service.report'],
            'Auditor' => ['audit.view', 'reports.export'],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::updateOrCreate(['slug' => strtolower($roleName)], ['name' => $roleName]);
            $permissionIds = Permission::whereIn('slug', $perms)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}
