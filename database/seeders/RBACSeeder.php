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
                'name' => 'Kelola Layanan & Verifikasi',
                'slug' => 'service.manage',
                'description' => 'Akses untuk mengelola layanan, menyetujui/menolak permohonan, dan konfigurasi verifikasi.',
            ],
            [
                'name' => 'Kelola Data Warga',
                'slug' => 'citizens.manage',
                'description' => 'Akses untuk mengelola data kependudukan warga termasuk tambah, ubah, dan hapus.',
            ],
            [
                'name' => 'Perbarui Data Kemiskinan',
                'slug' => 'poverty.update',
                'description' => 'Akses untuk memperbarui dan mencatat data kemiskinan warga.',
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

        // Define MPP (Mal Pelayanan Publik) Permissions
        $mppPermissions = [
            [
                'name' => 'Operasi Antrian MPP',
                'slug' => 'mpp.queue.operate',
                'description' => 'Akses untuk operasional antrian MPP: memanggil, meneruskan, menolak, mengingatkan, melewatkan, dan menyelesaikan tiket (FO & Gerai).',
            ],
            [
                'name' => 'Lihat Pengajuan MPP',
                'slug' => 'mpp.pengajuan.view',
                'description' => 'Akses untuk melihat daftar dan detail pengajuan layanan MPP.',
            ],
            [
                'name' => 'Ajukan Pengajuan MPP',
                'slug' => 'mpp.pengajuan.submit',
                'description' => 'Akses untuk membuat dan mengunggah pengajuan layanan MPP.',
            ],
            [
                'name' => 'Kelola Layanan MPP',
                'slug' => 'mpp.service.manage',
                'description' => 'Akses untuk mengelola daftar layanan/kerja sama MPP (tambah, ubah, hapus, unggah logo).',
            ],
            [
                'name' => 'Kelola Loket & Petugas MPP',
                'slug' => 'mpp.counter.manage',
                'description' => 'Akses untuk mengelola loket MPP dan penugasan petugas loket.',
            ],
            [
                'name' => 'Kelola Gerai MPP',
                'slug' => 'mpp.gerai.manage',
                'description' => 'Akses untuk mengelola daftar gerai MPP (tambah, ubah, hapus, unggah logo).',
            ],
            [
                'name' => 'Kelola Survei SKM',
                'slug' => 'mpp.skm.manage',
                'description' => 'Akses untuk mengelola dan mengekspor Survei Kepuasan Masyarakat (SKM).',
            ],
            [
                'name' => 'Kelola Pengaturan Display',
                'slug' => 'mpp.display.settings',
                'description' => 'Akses untuk mengatur tampilan Display Caller MPP (teks berjalan, video, header, suara, warna) dan melakukan test panggilan.',
            ],
        ];

        foreach ($mppPermissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Define Roles & Assign Permissions
        $roles = [
            'SuperAdmin' => ['service.verify', 'service.print_proof', 'service.report', 'service.manage', 'citizens.manage', 'poverty.update', 'audit.view', 'system.manage', 'users.manage', 'roles.manage', 'villages.manage', 'districts.manage', 'opds.manage', 'maps.manage', 'reports.export', 'mpp.queue.operate', 'mpp.pengajuan.view', 'mpp.pengajuan.submit', 'mpp.service.manage', 'mpp.counter.manage', 'mpp.gerai.manage', 'mpp.skm.manage', 'mpp.display.settings'],
            'OperatorDesa' => ['service.verify', 'mpp.pengajuan.view', 'mpp.pengajuan.submit'],
            'OperatorOpd' => ['service.verify', 'service.print_proof', 'service.report', 'mpp.pengajuan.view'],
            'PetugasFrontOffice' => ['service.verify', 'service.print_proof', 'service.report', 'service.manage', 'mpp.queue.operate', 'mpp.pengajuan.view', 'mpp.pengajuan.submit'],
            'Auditor' => ['audit.view', 'reports.export', 'mpp.pengajuan.view'],
            'Gerai' => ['service.verify', 'service.print_proof', 'mpp.queue.operate'],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::updateOrCreate(['slug' => strtolower($roleName)], ['name' => $roleName]);

            if ($roleName === 'SuperAdmin') {
                $permissionIds = Permission::pluck('id');
            } else {
                $permissionIds = Permission::whereIn('slug', $perms)->pluck('id');
            }

            $role->permissions()->sync($permissionIds);
        }
    }
}
