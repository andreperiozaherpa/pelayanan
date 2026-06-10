<?php

namespace Database\Seeders;

use App\Models\CmsAgenda;
use App\Models\CmsArticle;
use App\Models\CmsCategory;
use App\Models\CmsDocument;
use App\Models\CmsFaq;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsPortfolio;
use App\Models\CmsService;
use App\Models\CmsSetting;
use App\Models\CmsStatistic;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use App\Models\CmsWebsiteSection;
use App\Models\CmsWhyChooseUs;
use App\Models\User;
use Illuminate\Database\Seeder;

class DpmptspLandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. DPMPTSP Website Settings
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'DPMPTSP Kab. Tulang Bawang Barat', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_logo', 'value' => '', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => '', 'type' => 'string'],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'dpmptsp@tubabakab.go.id', 'type' => 'string'],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '+62 726-1234-567', 'type' => 'string'],
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Jl. Kompleks Perkantoran Pemda, Panaragan, Tulang Bawang Barat, Lampung', 'type' => 'string'],
            ['group' => 'contact', 'key' => 'contact_map_iframe', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.2644265432657!2d105.02324!3d-4.43234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNCsyNSc1Ni40IlMgMTA1wrAwMScyMy43IkU!5e0!3m2!1sid!2sid!4v1623232323232!5m2!1sid!2sid', 'type' => 'string'],
            ['group' => 'social', 'key' => 'social_facebook', 'value' => 'https://facebook.com/dpmptsptubaba', 'type' => 'string'],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => 'https://instagram.com/dpmptsp.tubaba', 'type' => 'string'],
            ['group' => 'social', 'key' => 'social_twitter', 'value' => 'https://twitter.com/dpmptsptubaba', 'type' => 'string'],
            ['group' => 'social', 'key' => 'social_youtube', 'value' => 'https://youtube.com/dpmptsptubaba', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'meta_title_default', 'value' => 'DPMPTSP Tulang Bawang Barat - Portal Penanaman Modal & Perizinan', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'meta_description_default', 'value' => 'Portal Resmi Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kabupaten Tulang Bawang Barat.', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'meta_keywords_default', 'value' => 'dpmptsp tubaba, perizinan tubaba, oss tubaba, simbg tubaba, investasi tubaba', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            CmsSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 2. Navigation Menus
        $menus = [
            // Level 1: Beranda
            ['title' => 'Beranda', 'url' => '/', 'order' => 1, 'children' => []],

            // Level 1: Profil
            ['title' => 'Profil', 'url' => '#', 'order' => 2, 'children' => [
                ['title' => 'Profil Dinas', 'url' => '/profil/dinas', 'order' => 1],
                ['title' => 'Visi dan Misi', 'url' => '/profil/visi-misi', 'order' => 2],
                ['title' => 'Struktur Organisasi', 'url' => '/profil/struktur', 'order' => 3],
                ['title' => 'Tugas dan Fungsi', 'url' => '/profil/tugas-fungsi', 'order' => 4],
                ['title' => 'Profil Pejabat', 'url' => '/profil/pejabat', 'order' => 5],
                ['title' => 'Sejarah Instansi', 'url' => '/profil/sejarah', 'order' => 6],
            ]],

            // Level 1: Pelayanan (Mega Menu)
            ['title' => 'Pelayanan', 'url' => '#', 'order' => 3, 'children' => [
                ['title' => 'Perizinan Berusaha', 'url' => '/pelayanan/perizinan-berusaha', 'order' => 1, 'description' => 'Panduan & persyaratan izin usaha berbasis resiko (OSS-RBA).'],
                ['title' => 'Perizinan Non Berusaha', 'url' => '/pelayanan/perizinan-non-berusaha', 'order' => 2, 'description' => 'Layanan izin praktik kesehatan, penelitian, dan izin non-usaha.'],
                ['title' => 'Persetujuan Bangunan Gedung (PBG)', 'url' => '/pelayanan/pbg', 'order' => 3, 'description' => 'Informasi pengurusan PBG pengganti IMB secara online.'],
                ['title' => 'Alur Penerbitan PBG', 'url' => '/pelayanan/alur-pbg', 'order' => 4, 'description' => 'Bagan alur pengurusan PBG dari awal hingga terbit.'],
                ['title' => 'Alur Prosedur Izin Penelitian', 'url' => '/pelayanan/alur-penelitian', 'order' => 5, 'description' => 'Mekanisme permohonan izin riset/survei di Tubaba.'],
                ['title' => 'Standar Pelayanan', 'url' => '/pelayanan/standar-pelayanan', 'order' => 6, 'description' => 'Maklumat, standar operasional, dan maklumat pelayanan.'],
                ['title' => 'SOP Pelayanan', 'url' => '/pelayanan/sop', 'order' => 7, 'description' => 'Standar Operasional Prosedur penanganan berkas izin.'],
                ['title' => 'Maklumat Pelayanan', 'url' => '/pelayanan/maklumat', 'order' => 8, 'description' => 'Pernyataan kesanggupan dinas memberikan pelayanan prima.'],
                ['title' => 'Kode Etik Pelayanan', 'url' => '/pelayanan/kode-etik', 'order' => 9, 'description' => 'Aturan perilaku aparatur pelayanan DPMPTSP.'],
                // Sistem Pelayanan Terintegrasi
                ['title' => 'OSS', 'url' => 'https://oss.go.id', 'target' => '_blank', 'order' => 10, 'description' => 'Sistem Perizinan Berusaha Terintegrasi Secara Elektronik.'],
                ['title' => 'SICANTIK', 'url' => 'https://sicantik.go.id', 'target' => '_blank', 'order' => 11, 'description' => 'Aplikasi Cerdas Layanan Perizinan Terpadu untuk Publik.'],
                ['title' => 'SIMBG', 'url' => 'https://simbg.pu.go.id', 'target' => '_blank', 'order' => 12, 'description' => 'Sistem Informasi Manajemen Bangunan Gedung.'],
                ['title' => 'SIBERUGO', 'url' => 'https://siberugo.go.id', 'target' => '_blank', 'order' => 13, 'description' => 'Portal pelayanan publik Tubaba terintegrasi.'],
                ['title' => 'GISTARU', 'url' => 'https://gistaru.atrbpn.go.id', 'target' => '_blank', 'order' => 14, 'description' => 'Geographic Information System Tata Ruang Nasional.'],
                ['title' => 'AMDALNET', 'url' => 'https://amdalnet.menlhk.go.id', 'target' => '_blank', 'order' => 15, 'description' => 'Sistem informasi dokumen lingkungan hidup.'],
            ]],

            // Level 1: Informasi Publik
            ['title' => 'Informasi Publik', 'url' => '#', 'order' => 4, 'children' => [
                ['title' => 'Berita', 'url' => '/informasi/berita', 'order' => 1],
                ['title' => 'Pengumuman', 'url' => '/informasi/pengumuman', 'order' => 2],
                ['title' => 'Agenda', 'url' => '/informasi/agenda', 'order' => 3],
                ['title' => 'Galeri', 'url' => '/informasi/galeri', 'order' => 4],
                ['title' => 'Dokumen Publik', 'url' => '/informasi/dokumen', 'order' => 5],
                ['title' => 'Regulasi', 'url' => '/informasi/regulasi', 'order' => 6],
            ]],

            // Level 1: Investasi
            ['title' => 'Investasi', 'url' => '#', 'order' => 5, 'children' => [
                ['title' => 'Potensi Investasi', 'url' => '/investasi/potensi', 'order' => 1],
                ['title' => 'Data Investasi', 'url' => '/investasi/data', 'order' => 2],
                ['title' => 'Peluang Investasi', 'url' => '/investasi/peluang', 'order' => 3],
                ['title' => 'Statistik Investasi', 'url' => '/investasi/statistik', 'order' => 4],
            ]],

            // Level 1: PPID
            ['title' => 'PPID', 'url' => '#', 'order' => 6, 'children' => [
                ['title' => 'Informasi Berkala', 'url' => '/ppid/berkala', 'order' => 1],
                ['title' => 'Informasi Serta Merta', 'url' => '/ppid/serta-merta', 'order' => 2],
                ['title' => 'Informasi Setiap Saat', 'url' => '/ppid/setiap-saat', 'order' => 3],
                ['title' => 'Permohonan Informasi', 'url' => '/ppid/permohonan', 'order' => 4],
            ]],

            // Level 1: Kontak
            ['title' => 'Kontak', 'url' => '#', 'order' => 7, 'children' => [
                ['title' => 'Kontak Kami', 'url' => '/kontak/kami', 'order' => 1],
                ['title' => 'Lokasi Kantor', 'url' => '/kontak/lokasi', 'order' => 2],
                ['title' => 'Pengaduan Masyarakat', 'url' => '/kontak/pengaduan', 'order' => 3],
            ]],
        ];

        // Seed menus dynamically
        CmsMenu::truncate();
        foreach ($menus as $parentData) {
            $children = $parentData['children'];
            unset($parentData['children']);

            $parent = CmsMenu::create(array_merge($parentData, [
                'parent_id' => null,
                'is_active' => true,
            ]));

            foreach ($children as $childData) {
                CmsMenu::create(array_merge($childData, [
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]));
            }
        }

        // 3. Website Sections (Sambutan Kadin & Banner)
        $sections = [
            [
                'key' => 'hero',
                'title' => 'Selamat Datang di Portal Resmi DPMPTSP Kabupaten Tulang Bawang Barat',
                'subtitle' => 'Dapatkan kemudahan pengurusan investasi, perizinan berusaha, dan non-berusaha dalam satu pintu secara cepat, mudah, dan transparan.',
                'content' => 'DPMPTSP Tubaba berkomitmen tinggi untuk terus berinovasi dalam mempermudah akses layanan publik demi mendukung pertumbuhan ekonomi daerah yang maju, inklusif, dan ramah investasi.',
                'image' => '',
                'button_text' => 'Ajukan Perizinan',
                'button_url' => 'https://oss.go.id',
            ],
            [
                'key' => 'vision',
                'title' => 'Visi Kami',
                'subtitle' => 'Terwujudnya Pelayanan Prima dan Iklim Investasi yang Kondusif',
                'content' => 'Mewujudkan DPMPTSP sebagai pusat pelayanan perizinan dan non-perizinan terpadu yang profesional, cepat, tepat, dan transparan guna mendukung iklim investasi yang kondusif di Kabupaten Tulang Bawang Barat.',
                'image' => '',
                'button_text' => null,
                'button_url' => null,
            ],
            [
                'key' => 'mission',
                'title' => 'Misi Kami',
                'subtitle' => 'Langkah Strategis Pencapaian Visi Dinas',
                'content' => '1. Meningkatkan kualitas pelayanan perizinan secara cepat, transparan, dan akuntabel. 2. Meningkatkan kegiatan promosi dan kerja sama penanaman modal. 3. Menyempurnakan regulasi dan kebijakan kemudahan berusaha di daerah. 4. Memanfaatkan sistem perizinan berbasis elektronik secara optimal.',
                'image' => '',
                'button_text' => null,
                'button_url' => null,
            ],
            [
                'key' => 'cta',
                'title' => 'Ingin Berkonsultasi Mengenai Investasi & Perizinan?',
                'subtitle' => 'Tim helpdesk kami siap membimbing Anda dari awal pendaftaran hingga sertifikat izin diterbitkan.',
                'content' => null,
                'image' => '',
                'button_text' => 'Hubungi Kontak Kami',
                'button_url' => '/kontak/kami',
            ],
        ];

        foreach ($sections as $section) {
            CmsWebsiteSection::updateOrCreate(['key' => $section['key']], $section);
        }

        // 4. CMS Pages (Profil & Pelayanan Sub-menus)
        $pages = [
            // Profil Pages
            [
                'slug' => 'dinas',
                'title' => 'Profil Dinas',
                'content' => '<p class="mb-4">Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kabupaten Tulang Bawang Barat merupakan instansi pemerintah daerah yang bertugas merumuskan kebijakan, menyelenggarakan pelayanan, serta mempromosikan potensi penanaman modal secara terpadu di wilayah Kabupaten Tulang Bawang Barat.</p><p>Sebagai beranda investasi dan perizinan daerah, kami berkomitmen memberikan pelayanan prima melalui penyederhanaan birokrasi, digitalisasi layanan, dan peningkatan transparansi.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'visi-misi',
                'title' => 'Visi dan Misi',
                'content' => '<h3 class="text-xl font-bold text-[#5d1e25] mb-2">Visi Dinas</h3><p class="mb-6">Terwujudnya Pelayanan Prima dan Iklim Investasi yang Kondusif untuk Kemajuan Kabupaten Tulang Bawang Barat.</p><h3 class="text-xl font-bold text-[#5d1e25] mb-2">Misi Dinas</h3><ul class="list-decimal pl-5 space-y-2"><li>Meningkatkan kualitas pelayanan perizinan secara cepat, transparan, dan akuntabel.</li><li>Meningkatkan kegiatan promosi dan kerja sama penanaman modal yang berdaya saing.</li><li>Menyempurnakan regulasi dan kebijakan kemudahan berusaha di daerah.</li><li>Memanfaatkan sistem perizinan berbasis elektronik (OSS & SICANTIK) secara optimal.</li></ul>',
                'status' => 'published',
            ],
            [
                'slug' => 'struktur',
                'title' => 'Struktur Organisasi',
                'content' => '<p class="mb-4">Struktur Organisasi DPMPTSP Kabupaten Tulang Bawang Barat disusun berdasarkan Peraturan Daerah untuk menciptakan alur koordinasi yang dinamis dan efektif:</p><div class="border border-stone-300 rounded-xl overflow-hidden mb-6"><table class="min-w-full divide-y divide-stone-300"><thead class="bg-stone-100"><tr><th class="px-4 py-2 text-left font-bold text-sm text-[#5d1e25]">Jabatan</th><th class="px-4 py-2 text-left font-bold text-sm text-[#5d1e25]">Nama Pejabat</th></tr></thead><tbody class="divide-y divide-stone-200"><tr><td class="px-4 py-2 text-sm font-semibold">Kepala Dinas</td><td class="px-4 py-2 text-sm">Drs. H. Syahrul, M.IP.</td></tr><tr><td class="px-4 py-2 text-sm font-semibold">Sekretaris Dinas</td><td class="px-4 py-2 text-sm">Ahmad Fauzi, S.E.</td></tr><tr><td class="px-4 py-2 text-sm font-semibold">Kabid Penanaman Modal</td><td class="px-4 py-2 text-sm">Ir. Herry Wibowo, M.T.</td></tr><tr><td class="px-4 py-2 text-sm font-semibold">Kabid Pelayanan Perizinan</td><td class="px-4 py-2 text-sm">Siti Nurhayati, S.E.</td></tr></tbody></table></div>',
                'status' => 'published',
            ],
            [
                'slug' => 'tugas-fungsi',
                'title' => 'Tugas dan Fungsi',
                'content' => '<p class="mb-4 font-semibold text-[#5d1e25]">Tugas Pokok:</p><p class="mb-6">Melaksanakan urusan pemerintahan daerah di bidang penanaman modal dan pelayanan terpadu satu pintu berdasarkan asas otonomi dan tugas pembantuan.</p><p class="mb-4 font-semibold text-[#5d1e25]">Fungsi Utama:</p><ul class="list-disc pl-5 space-y-2"><li>Perumusan kebijakan teknis di bidang penanaman modal dan pelayanan perizinan.</li><li>Penyelenggaraan pelayanan administratif perizinan dan non-perizinan terpadu.</li><li>Pelaksanaan pemantauan, pembinaan, dan pengawasan pelaksanaan penanaman modal.</li><li>Evaluasi dan pelaporan kinerja pelayanan secara berkala.</li></ul>',
                'status' => 'published',
            ],
            [
                'slug' => 'pejabat',
                'title' => 'Profil Pejabat',
                'content' => '<p class="mb-6">Berikut adalah profil singkat jajaran pimpinan tinggi di lingkungan Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kabupaten Tulang Bawang Barat:</p><div class="space-y-6"><div class="flex flex-col md:flex-row gap-6 p-6 bg-white/30 rounded-2xl border border-white/20"><div class="w-24 h-24 rounded-full bg-stone-200 flex-shrink-0 flex items-center justify-center font-bold text-[#5d1e25]">HS</div><div><h4 class="text-lg font-bold text-[#5d1e25]">Drs. H. Syahrul, M.IP.</h4><p class="text-sm font-semibold text-stone-500 mb-2">Kepala Dinas DPMPTSP</p><p class="text-sm">Bertanggung jawab penuh atas perumusan kebijakan strategis dan pelaksanaan reformasi birokrasi di lingkungan DPMPTSP Tubaba.</p></div></div></div>',
                'status' => 'published',
            ],
            [
                'slug' => 'sejarah',
                'title' => 'Sejarah Instansi',
                'content' => '<p class="mb-4">Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kabupaten Tulang Bawang Barat dibentuk sejalan dengan pemekaran Kabupaten Tulang Bawang Barat pada tahun 2008.</p><p>Sejak awal berdirinya, instansi ini terus bertransformasi dari sistem pelayanan manual satu atap hingga menjadi Pelayanan Terpadu Satu Pintu (PTSP) berbasis digital terintegrasi penuh seperti yang beroperasi saat ini.</p>',
                'status' => 'published',
            ],

            // Pelayanan Pages
            [
                'slug' => 'perizinan-berusaha',
                'title' => 'Perizinan Berusaha',
                'content' => '<p class="mb-4">Perizinan Berusaha di Kabupaten Tulang Bawang Barat kini dilayani secara elektronik terintegrasi melalui sistem <strong>Online Single Submission (OSS)</strong> berbasis risiko.</p><p class="mb-4 font-semibold text-[#5d1e25]">Kategori Pelaku Usaha:</p><ul class="list-disc pl-5 space-y-2"><li><strong>Usaha Mikro Kecil (UMK)</strong>: Untuk usaha perorangan dengan modal usaha tertentu. NIB berlaku sebagai perizinan tunggal.</li><li><strong>Non-Usaha Mikro Kecil (Non-UMK)</strong>: Untuk badan usaha skala menengah dan besar yang memerlukan verifikasi teknis pemenuhan standar.</li></ul>',
                'status' => 'published',
            ],
            [
                'slug' => 'perizinan-non-berusaha',
                'title' => 'Perizinan Non Berusaha',
                'content' => '<p class="mb-4">Perizinan Non Berusaha mencakup layanan perizinan sektoral daerah seperti Surat Izin Praktik (SIP) tenaga kesehatan, izin operasional sekolah swasta, serta rekomendasi penelitian daerah.</p><p>Pelayanan ini diajukan secara online melalui aplikasi <strong>SICANTIK Cloud</strong> dan diverifikasi langsung oleh tim teknis dinas terkait.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'pbg',
                'title' => 'Persetujuan Bangunan Gedung (PBG)',
                'content' => '<p class="mb-4">Persetujuan Bangunan Gedung (PBG) adalah perizinan yang diberikan kepada pemilik bangunan gedung untuk membangun baru, mengubah, memperluas, mengurangi, dan/atau merawat bangunan gedung sesuai dengan standar teknis yang berlaku.</p><p>Pengajuan PBG dilakukan secara mandiri oleh pemohon melalui portal <strong>SIMBG</strong> Kementerian PUPR.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'alur-pbg',
                'title' => 'Alur Penerbitan PBG',
                'content' => '<p class="mb-4">Mekanisme permohonan Persetujuan Bangunan Gedung (PBG) mengikuti langkah-langkah berikut:</p><ol class="list-decimal pl-5 space-y-2"><li>Pendaftaran akun pemohon di SIMBG.</li><li>Pemberkasan dokumen administratif & teknis arsitektur/struktur.</li><li>Verifikasi kelengkapan berkas oleh dinas teknis PUPR.</li><li>Sidang penilaian teknis oleh Tim Profesi Ahli (TPA) / Tim Teknis.</li><li>Penghitungan retribusi daerah dan pembayaran oleh pemohon.</li><li>Penerbitan dokumen PBG secara elektronik oleh DPMPTSP.</li></ol>',
                'status' => 'published',
            ],
            [
                'slug' => 'alur-penelitian',
                'title' => 'Alur Prosedur Izin Penelitian',
                'content' => '<p class="mb-4">Prosedur pengajuan Surat Keterangan Penelitian (SKP) di Kabupaten Tulang Bawang Barat:</p><ol class="list-decimal pl-5 space-y-2"><li>Pemohon mengunggah proposal penelitian dan surat pengantar dari universitas/instansi asal ke SICANTIK Cloud.</li><li>Dinas Kesbangpol memverifikasi kesesuaian materi riset dan menerbitkan rekomendasi.</li><li>DPMPTSP melakukan otentikasi dokumen akhir dan menerbitkan SKP resmi secara digital.</li></ol>',
                'status' => 'published',
            ],
            [
                'slug' => 'standar-pelayanan',
                'title' => 'Standar Pelayanan',
                'content' => '<p class="mb-4">Standar Pelayanan DPMPTSP Kabupaten Tulang Bawang Barat disusun sebagai acuan kepastian bagi masyarakat dan wujud transparansi aparatur:</p><p class="mb-4">Setiap produk perizinan memiliki spesifikasi layanan yang meliputi jangka waktu penyelesaian berkas, persyaratan wajib, alur sirkulasi berkas, hingga penyediaan jalur pengaduan masyarakat.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'sop',
                'title' => 'SOP Pelayanan',
                'content' => '<p class="mb-4">Standar Operasional Prosedur (SOP) internal DPMPTSP mengatur proses penerimaan berkas, pemrosesan verifikasi administrasi di Front Office, peninjauan lapangan oleh tim teknis Back Office, hingga penandatanganan dokumen secara elektronik (TTE) oleh Kepala Dinas.</p>',
                'status' => 'published',
            ],
            [
                'slug' => 'maklumat',
                'title' => 'Maklumat Pelayanan',
                'content' => '<blockquote class="border-l-4 border-[#7b323b] pl-4 italic text-lg text-[#5d1e25] my-6">"Dengan ini kami menyatakan kesanggupan untuk menyelenggarakan pelayanan sesuai dengan standar pelayanan yang telah ditetapkan, dan apabila tidak menepati janji, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."</blockquote>',
                'status' => 'published',
            ],
            [
                'slug' => 'kode-etik',
                'title' => 'Kode Etik Pelayanan',
                'content' => '<p class="mb-4">Seluruh aparatur pelayanan DPMPTSP Kabupaten Tulang Bawang Barat wajib menjunjung tinggi etika pelayanan publik:</p><ul class="list-disc pl-5 space-y-2"><li>Bersikap 5S (Senyum, Sapa, Salam, Sopan, Santun).</li><li>Menjaga integritas tinggi dan menolak segala bentuk gratifikasi.</li><li>Mengutamakan profesionalisme dan objektivitas penilaian berkas.</li></ul>',
                'status' => 'published',
            ],
        ];

        foreach ($pages as $pageData) {
            CmsPage::updateOrCreate(['slug' => $pageData['slug']], $pageData);
        }

        // 5. Statistics Pelayanan
        $statistics = [
            ['icon' => 'lucide:file-check', 'label' => 'Jumlah Izin Terbit', 'value' => 14205, 'order' => 1, 'is_active' => true],
            ['icon' => 'lucide:badge-dollar-sign', 'label' => 'Jumlah Investasi (Miliar)', 'value' => 450, 'order' => 2, 'is_active' => true],
            ['icon' => 'lucide:users-2', 'label' => 'Jumlah Pelaku Usaha', 'value' => 8930, 'order' => 3, 'is_active' => true],
            ['icon' => 'lucide:monitor-smartphone', 'label' => 'Jumlah Layanan Online', 'value' => 6, 'order' => 4, 'is_active' => true],
        ];

        foreach ($statistics as $stat) {
            CmsStatistic::updateOrCreate(['label' => $stat['label']], $stat);
        }

        // 6. Quick Access Services
        $services = [
            ['icon' => 'lucide:globe', 'name' => 'OSS (Online Single Submission)', 'summary' => 'Perizinan berusaha berbasis risiko untuk pelaku usaha mikro, kecil, menengah, dan besar.', 'description' => 'oss', 'link_url' => 'https://oss.go.id', 'order' => 1, 'is_active' => true],
            ['icon' => 'lucide:file-signature', 'name' => 'SICANTIK Cloud', 'summary' => 'Aplikasi cerdas untuk pengurusan perizinan non-berusaha sektoral daerah.', 'description' => 'sicantik', 'link_url' => 'https://sicantik.go.id', 'order' => 2, 'is_active' => true],
            ['icon' => 'lucide:building', 'name' => 'SIMBG', 'summary' => 'Sistem informasi terintegrasi untuk pengajuan Persetujuan Bangunan Gedung (PBG) dan SLF.', 'description' => 'simbg', 'link_url' => 'https://simbg.pu.go.id', 'order' => 3, 'is_active' => true],
            ['icon' => 'lucide:laptop', 'name' => 'SIBERUGO', 'summary' => 'Platform integrasi layanan publik Kabupaten Tulang Bawang Barat.', 'description' => 'siberugo', 'link_url' => 'https://siberugo.go.id', 'order' => 4, 'is_active' => true],
            ['icon' => 'lucide:map', 'name' => 'GISTARU', 'summary' => 'Layanan pemetaan tata ruang nasional untuk kesesuaian rencana detail tata ruang (RDTR).', 'description' => 'gistaru', 'link_url' => 'https://gistaru.atrbpn.go.id', 'order' => 5, 'is_active' => true],
            ['icon' => 'lucide:leaf', 'name' => 'AMDALNET', 'summary' => 'Sistem informasi kelayakan lingkungan hidup untuk penerbitan izin persetujuan lingkungan.', 'description' => 'amdalnet', 'link_url' => 'https://amdalnet.menlhk.go.id', 'order' => 6, 'is_active' => true],
        ];

        CmsService::truncate();
        foreach ($services as $service) {
            CmsService::create($service);
        }

        // 7. Why Choose Us (Standar Pelayanan / Nilai Utama)
        $whyChooseUs = [
            ['icon' => 'lucide:check-circle-2', 'title' => 'Transparan & Akuntabel', 'description' => 'Seluruh biaya retribusi, alur, dan waktu pemrosesan izin diumumkan secara transparan sesuai aturan.', 'order' => 1, 'is_active' => true],
            ['icon' => 'lucide:shield-check', 'title' => 'Bebas Pungutan Liar (Pungli)', 'description' => 'DPMPTSP berkomitmen menjaga integritas pelayanan tanpa biaya tambahan di luar regulasi resmi daerah.', 'order' => 2, 'is_active' => true],
            ['icon' => 'lucide:gauge', 'title' => 'Proses Mudah Secara Daring', 'description' => 'Masyarakat tidak perlu mengantre fisik, berkas diunggah dan diverifikasi secara online.', 'order' => 3, 'is_active' => true],
        ];

        CmsWhyChooseUs::truncate();
        foreach ($whyChooseUs as $wcu) {
            CmsWhyChooseUs::create($wcu);
        }

        // 8. Categories
        $catBerita = CmsCategory::updateOrCreate(['slug' => 'berita'], ['name' => 'Berita Kegiatan', 'description' => 'Berita seputar kegiatan penanaman modal dan perizinan.']);
        $catPengumuman = CmsCategory::updateOrCreate(['slug' => 'pengumuman'], ['name' => 'Pengumuman Resmi', 'description' => 'Surat edaran, pengumuman publik, dan maklumat resmi.']);

        // 9. Articles (6 News Items)
        $author = User::first();
        $articles = [
            [
                'title' => 'Sosialisasi Implementasi Perizinan Berusaha Berbasis Risiko (OSS-RBA)',
                'slug' => 'sosialisasi-implementasi-perizinan-berusaha-berbasis-risiko',
                'excerpt' => 'DPMPTSP menggelar bimbingan teknis kemudahan berusaha bagi para pelaku UMKM lokal di Tubaba.',
                'content' => 'Kegiatan ini diselenggarakan guna meningkatkan pemahaman wirausaha di Kabupaten Tulang Bawang Barat mengenai regulasi terbaru OSS-RBA pasca berlakunya Undang-Undang Cipta Kerja.',
                'featured_image' => '',
                'category_id' => $catBerita->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Realisasi Target Investasi Kabupaten Tubaba Lampaui 120%',
                'slug' => 'realisasi-target-investasi-kabupaten-tubaba-lampaui-120-persen',
                'excerpt' => 'DPMPTSP mencatat lonjakan nilai realisasi investasi di sektor ketahanan pangan dan perkebunan.',
                'content' => 'Realisasi investasi pada tahun ini naik signifikan yang didominasi oleh investasi PMDN pada industri pengolahan singkong dan tebu di wilayah utara Tubaba.',
                'featured_image' => '',
                'category_id' => $catBerita->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'DPMPTSP Permudah Izin Praktik Tenaga Kesehatan Lewat SICANTIK Cloud',
                'slug' => 'dpmptsp-permudah-izin-praktik-tenaga-kesehatan-lewat-sicantik-cloud',
                'excerpt' => 'Layanan Surat Izin Praktik (SIP) dokter, bidan, dan perawat kini terintegrasi penuh secara digital.',
                'content' => 'Melalui SICANTIK Cloud, para tenaga kesehatan di Tubaba tidak perlu menyerahkan berkas fisik ke kantor dinas, melainkan cukup mengunggah berkas rekomendasi profesi secara online.',
                'featured_image' => '',
                'category_id' => $catBerita->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Penerapan Sistem Baru SIMBG untuk Pembangunan Gedung Bebas Hambatan',
                'slug' => 'penerapan-sistem-baru-simbg-untuk-pembangunan-gedung-bebas-hambatan',
                'excerpt' => 'Masyarakat dihimbau mengajukan Persetujuan Bangunan Gedung (PBG) pengganti IMB melalui SIMBG.',
                'content' => 'SIMBG memfasilitasi koordinasi tim ahli bangunan gedung (TABG) pemda dalam meninjau rencana konstruksi pemohon secara digital.',
                'featured_image' => '',
                'category_id' => $catBerita->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Kunjungan Lapangan Pengawasan Penanaman Modal di Kecamatan Tulang Bawang Tengah',
                'slug' => 'kunjungan-lapangan-pengawasan-penanaman-modal',
                'excerpt' => 'Tim Pengawasan DPMPTSP melakukan tinjauan lapangan untuk memastikan kepatuhan LKPM pelaku usaha.',
                'content' => 'Pengawasan rutin dilaksanakan agar pelaku usaha mematuhi kewajiban penyampaian Laporan Kegiatan Penanaman Modal (LKPM) setiap triwulan.',
                'featured_image' => '',
                'category_id' => $catBerita->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Maklumat Pelayanan DPMPTSP: Siap Melayani dengan Ramah dan Cepat',
                'slug' => 'maklumat-pelayanan-dpmptsp-siap-melayani-dengan-ramah-dan-cepat',
                'excerpt' => 'Kami berkomitmen mewujudkan wilayah bebas korupsi dan memberikan pelayanan tanpa diskriminasi.',
                'content' => 'Sebagai komitmen nyata reformasi birokrasi, seluruh staf pelayanan DPMPTSP menandatangani maklumat kesiapan pelayanan prima demi kepuasan publik.',
                'featured_image' => '',
                'category_id' => $catPengumuman->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
        ];

        CmsArticle::truncate();
        foreach ($articles as $article) {
            CmsArticle::create($article);
        }

        // 10. Portfolios (Peluang & Potensi Investasi Unggulan)
        $portfolios = [
            ['thumbnail' => '', 'name' => 'Kawasan Industri Singkong & Tapioka Terpadu', 'client' => 'Peluang Investasi', 'category_id' => $catBerita->id, 'is_active' => true],
            ['thumbnail' => '', 'name' => 'Pusat Agrowisata Buah & Sayur Tubaba', 'client' => 'Potensi Daerah', 'category_id' => $catBerita->id, 'is_active' => true],
            ['thumbnail' => '', 'name' => 'Sentra Kerajinan Tangan Bambu Kreatif', 'client' => 'Ekonomi Kreatif', 'category_id' => $catBerita->id, 'is_active' => true],
        ];

        CmsPortfolio::truncate();
        foreach ($portfolios as $portfolio) {
            CmsPortfolio::create($portfolio);
        }

        // 11. Teams (Aparatur Pejabat)
        $teams = [
            ['name' => 'Drs. H. Syahrul, M.IP.', 'position' => 'Kepala Dinas DPMPTSP', 'image' => '', 'social_links' => ['linkedin' => '#'], 'order' => 1, 'is_active' => true],
            ['name' => 'Ir. Herry Wibowo, M.T.', 'position' => 'Kabid Penanaman Modal', 'image' => '', 'social_links' => ['twitter' => '#'], 'order' => 2, 'is_active' => true],
            ['name' => 'Siti Nurhayati, S.E.', 'position' => 'Kabid Pelayanan Perizinan', 'image' => '', 'social_links' => ['facebook' => '#'], 'order' => 3, 'is_active' => true],
        ];

        CmsTeam::truncate();
        foreach ($teams as $member) {
            CmsTeam::create($member);
        }

        // 12. Testimonials (Pelaku Usaha)
        $testimonials = [
            [
                'name' => 'I Wayan Sudarta',
                'position' => 'Direktur Utama',
                'company' => 'PT Tubaba Agro Lestari',
                'content' => 'Proses pengawalan investasi dari DPMPTSP sangat profesional. Kami diberikan asistensi penuh dalam pengurusan izin prinsip hingga operational clearance.',
                'rating' => 5,
                'avatar' => '',
                'is_active' => true,
            ],
            [
                'name' => 'Rini Astuti',
                'position' => 'Pemilik Usaha',
                'company' => 'Rini Cake & Bakery',
                'content' => 'Mengurus Izin Usaha Mikro Kecil (IUMK) lewat OSS dibantu pendampingan oleh dinas sangat mudah. Selesai dalam hitungan menit dan gratis!',
                'rating' => 5,
                'avatar' => '',
                'is_active' => true,
            ],
        ];

        CmsTestimonial::truncate();
        foreach ($testimonials as $testimonial) {
            CmsTestimonial::create($testimonial);
        }

        // 13. FAQs
        $faqs = [
            [
                'question' => 'Bagaimana cara mendaftarkan izin usaha mikro kecil (UMK)?',
                'answer' => 'Anda dapat mendaftar secara mandiri melalui website OSS (oss.go.id) menggunakan NIK KTP Anda. Proses ini gratis dan NIB langsung terbit.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apa syarat utama pengajuan Persetujuan Bangunan Gedung (PBG)?',
                'answer' => 'Syarat utamanya meliputi bukti kepemilikan tanah, gambar rencana teknis arsitektur & struktur bangunan, serta dokumen lingkungan sesuai peruntukan.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa biaya retribusi untuk mengurus izin perizinan di DPMPTSP?',
                'answer' => 'Mayoritas izin tidak dipungut biaya retribusi (gratis), kecuali retribusi tertentu seperti Persetujuan Bangunan Gedung (PBG) yang dihitung resmi berdasarkan luas dan fungsi bangunan.',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        CmsFaq::truncate();
        foreach ($faqs as $faq) {
            CmsFaq::create($faq);
        }

        // 14. Documents & Regulations
        CmsDocument::truncate();
        CmsDocument::create([
            'title' => 'Perda No. 5 Tahun 2024 tentang Rencana Tata Ruang Wilayah Tubaba',
            'description' => 'Dokumen resmi rencana detail tata ruang dan penataan kawasan kabupaten.',
            'file_path' => 'documents/perda_rtrw_2024.pdf',
            'type' => 'regulasi',
            'downloads_count' => 128,
            'is_active' => true,
        ]);
        CmsDocument::create([
            'title' => 'Standar Pelayanan Penerbitan Izin Praktik Tenaga Kesehatan',
            'description' => 'Persyaratan, alur, waktu, dan mekanisme pengaduan izin praktik medis.',
            'file_path' => 'documents/sp_nakes_2025.pdf',
            'type' => 'public',
            'downloads_count' => 342,
            'is_active' => true,
        ]);

        // 15. Agendas
        CmsAgenda::truncate();
        CmsAgenda::create([
            'title' => 'Bimtek dan Pendampingan Pembuatan LKPM Triwulan II',
            'description' => 'Pelatihan pengisian Laporan Kegiatan Penanaman Modal bagi pelaku usaha menengah besar.',
            'location' => 'Aula Hotel Tubaba, Tulang Bawang Tengah',
            'start_date' => now()->addDays(5)->setHour(9)->setMinute(0),
            'end_date' => now()->addDays(5)->setHour(13)->setMinute(0),
            'is_active' => true,
        ]);
    }
}
