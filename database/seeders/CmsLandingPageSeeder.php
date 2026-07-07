<?php

namespace Database\Seeders;

use App\Models\CmsArticle;
use App\Models\CmsCategory;
use App\Models\CmsFaq;
use App\Models\CmsPage;
use App\Models\CmsPortfolio;
use App\Models\CmsService;
use App\Models\CmsStatistic;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use App\Models\CmsWebsiteSection;
use App\Models\CmsWhyChooseUs;
use App\Models\User;
use Illuminate\Database\Seeder;

class CmsLandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Website Sections
        $sections = [
            [
                'key' => 'hero',
                'title' => 'Portal Layanan Terintegrasi & Informasi Publik',
                'subtitle' => 'Mempermudah masyarakat mengakses berbagai layanan administrasi secara digital, transparan, dan responsif.',
                'content' => 'Layanan Publik Terpadu Kabupaten Tulang Bawang Barat hadir dengan komitmen tinggi untuk memberikan kemudahan akses perizinan, administrasi kependudukan, serta layanan publik sektoral lainnya secara prima.',
                'image' => null,
                'button_text' => 'Mulai Layanan',
                'button_url' => '/login',
            ],
            [
                'key' => 'vision',
                'title' => 'Visi Kami',
                'subtitle' => 'Mewujudkan Pelayanan Publik yang Prima dan Akuntabel',
                'content' => 'Menjadi pusat pelayanan publik terintegrasi berbasis teknologi informasi yang profesional, transparan, ramah, dan berorientasi pada kepuasan masyarakat demi terwujudnya Tulang Bawang Barat yang maju dan sejahtera.',
                'image' => null,
                'button_text' => null,
                'button_url' => null,
            ],
            [
                'key' => 'mission',
                'title' => 'Misi Kami',
                'subtitle' => 'Langkah Nyata Mewujudkan Visi Pelayanan',
                'content' => '1. Menyelenggarakan pelayanan administrasi satu pintu yang cepat dan efisien. 2. Memanfaatkan teknologi informasi secara terintegrasi untuk transparansi layanan. 3. Meningkatkan kompetensi aparatur pelayanan agar bersikap profesional dan ramah. 4. Melakukan evaluasi berkala berdasarkan umpan balik masyarakat.',
                'image' => null,
                'button_text' => null,
                'button_url' => null,
            ],
            [
                'key' => 'cta',
                'title' => 'Butuh Bantuan atau Ingin Mengajukan Layanan?',
                'subtitle' => 'Daftar sekarang dan nikmati kemudahan pengurusan administrasi tanpa antre secara daring.',
                'content' => null,
                'image' => null,
                'button_text' => 'Daftar Sekarang',
                'button_url' => '/register',
            ],
        ];

        foreach ($sections as $section) {
            CmsWebsiteSection::updateOrCreate(['key' => $section['key']], $section);
        }

        // 2. About Us Page
        CmsPage::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'Tentang Kami',
                'content' => 'Pusat Pelayanan Publik Siberugo adalah inisiatif strategis pemerintah daerah untuk mempermudah akses layanan publik bagi seluruh lapisan masyarakat. Di bawah naungan MPP Tulang Bawang Barat, kami menghadirkan kolaborasi antarinstansi (Dispendukcapil, Dinas Sosial, Dinas Kesehatan, dll) dalam satu platform digital terpadu.',
                'status' => 'published',
            ]
        );

        // 3. Statistics
        $statistics = [
            ['icon' => 'lucide:users', 'label' => 'Masyarakat Terlayani', 'value' => 25800, 'order' => 1, 'is_active' => true],
            ['icon' => 'lucide:file-text', 'label' => 'Dokumen Diproses', 'value' => 42100, 'order' => 2, 'is_active' => true],
            ['icon' => 'lucide:building-2', 'label' => 'OPD Terintegrasi', 'value' => 18, 'order' => 3, 'is_active' => true],
            ['icon' => 'lucide:smile', 'label' => 'Tingkat Kepuasan', 'value' => 96, 'order' => 4, 'is_active' => true],
        ];

        CmsStatistic::truncate();
        foreach ($statistics as $stat) {
            CmsStatistic::create($stat);
        }

        // 4. Services
        $services = [
            [
                'icon' => 'lucide:user-check',
                'name' => 'Layanan Kependudukan',
                'summary' => 'Pengurusan KTP, Kartu Keluarga, Akta Kelahiran, dan dokumen sipil lainnya secara online.',
                'description' => 'Layanan ini terintegrasi langsung dengan Dinas Kependudukan dan Pencatatan Sipil guna memproses pengajuan dokumen identitas diri Anda secara cepat dan aman.',
                'link_url' => '/layanan/kependudukan',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => 'lucide:shield-alert',
                'name' => 'Bantuan Sosial',
                'summary' => 'Pendaftaran, verifikasi, dan monitoring penerima program bantuan sosial daerah.',
                'description' => 'Melalui kerja sama dengan Dinas Sosial, kami memvalidasi data kemiskinan dan kelayakan penerima bantuan agar tepat sasaran.',
                'link_url' => '/layanan/bansos',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'icon' => 'lucide:heart-pulse',
                'name' => 'Layanan Kesehatan',
                'summary' => 'Pendaftaran fasilitas kesehatan, rujukan JKN-KIS, dan informasi jadwal puskesmas.',
                'description' => 'Nikmati akses informasi kesehatan dan pengurusan jaminan kesehatan daerah secara transparan.',
                'link_url' => '/layanan/kesehatan',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'icon' => 'lucide:briefcase',
                'name' => 'Perizinan Usaha',
                'summary' => 'Fasilitasi izin usaha mikro kecil (IUMK) dan rekomendasi perizinan sektoral.',
                'description' => 'Kami membantu mempermudah para pelaku UMKM lokal untuk melegalkan bisnis mereka secara cepat.',
                'link_url' => '/layanan/perizinan',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            CmsService::updateOrCreate(['name' => $service['name']], $service);
        }

        // 5. Why Choose Us
        $whyChooseUs = [
            [
                'icon' => 'lucide:zap',
                'title' => 'Proses Cepat & Mudah',
                'description' => 'Pengajuan berkas secara online mempersingkat waktu tunggu dan antrean fisik di kantor dinas.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => 'lucide:shield-check',
                'title' => 'Transparan & Terlacak',
                'description' => 'Masyarakat dapat memantau status pengajuan secara real-time dari dasbor akun pribadi.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'icon' => 'lucide:headset',
                'title' => 'Dukungan Helpdesk Prima',
                'description' => 'Tim operator kami siap memberikan panduan dan menjawab pertanyaan Anda dengan ramah.',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($whyChooseUs as $wcu) {
            CmsWhyChooseUs::updateOrCreate(['title' => $wcu['title']], $wcu);
        }

        // 6. Categories for Articles & Portfolio
        $catNews = CmsCategory::updateOrCreate(
            ['slug' => 'berita'],
            ['name' => 'Berita & Pengumuman', 'description' => 'Berita resmi dari Kabupaten Tulang Bawang Barat']
        );

        $catLayanan = CmsCategory::updateOrCreate(
            ['slug' => 'layanan'],
            ['name' => 'Tutorial & Layanan', 'description' => 'Panduan pengurusan layanan publik']
        );

        // 7. Portfolios (Projects / Instansi Terintegrasi)
        $portfolios = [
            ['thumbnail' => '', 'name' => 'Integrasi Dispendukcapil Online', 'client' => 'Dispendukcapil', 'category_id' => $catLayanan->id, 'is_active' => true],
            ['thumbnail' => '', 'name' => 'Sistem Verifikasi Bansos Terpadu', 'client' => 'Dinas Sosial', 'category_id' => $catLayanan->id, 'is_active' => true],
            ['thumbnail' => '', 'name' => 'Portal Pendaftaran Puskesmas TBT', 'client' => 'Dinas Kesehatan', 'category_id' => $catLayanan->id, 'is_active' => true],
        ];

        foreach ($portfolios as $portfolio) {
            CmsPortfolio::updateOrCreate(['name' => $portfolio['name']], $portfolio);
        }

        // 8. Teams
        $teams = [
            ['name' => 'Budi Santoso, M.T.', 'position' => 'Kepala Dinas Kominfo', 'image' => '', 'social_links' => ['twitter' => '#', 'linkedin' => '#'], 'order' => 1, 'is_active' => true],
            ['name' => 'Dewi Lestari, S.Kom.', 'position' => 'Koordinator IT & Integrasi', 'image' => '', 'social_links' => ['instagram' => '#', 'linkedin' => '#'], 'order' => 2, 'is_active' => true],
            ['name' => 'Adi Wijaya, A.Md.', 'position' => 'Supervisor Front Office', 'image' => '', 'social_links' => ['facebook' => '#'], 'order' => 3, 'is_active' => true],
        ];

        foreach ($teams as $member) {
            CmsTeam::updateOrCreate(['name' => $member['name']], $member);
        }

        // 9. Testimonials
        $testimonials = [
            [
                'name' => 'Hendra Wijaya',
                'position' => 'Wirausaha',
                'company' => 'TBT Souvenir',
                'content' => 'Pengurusan izin IUMK secara online lewat portal Siberugo sangat membantu. Hanya butuh waktu 1 hari tanpa keluar rumah.',
                'rating' => 5,
                'avatar' => '',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'position' => 'Ibu Rumah Tangga',
                'company' => null,
                'content' => 'Sangat praktis untuk memperbarui data Kartu Keluarga pasca melahirkan. Tinggal upload berkas, diverifikasi, lalu cetak sendiri.',
                'rating' => 5,
                'avatar' => '',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            CmsTestimonial::updateOrCreate(['name' => $testimonial['name']], $testimonial);
        }

        // 10. Articles
        $author = User::first();
        $articles = [
            [
                'title' => 'Peluncuran Layanan Siberugo untuk Warga Tubaba',
                'slug' => 'peluncuran-layanan-siberugo-untuk-warga-tubaba',
                'excerpt' => 'Kini seluruh pengurusan administrasi dapat diakses secara digital melalui portal terpadu Siberugo.',
                'content' => 'Dalam rangka meningkatkan transparansi dan kecepatan pelayanan publik, Pemerintah Kabupaten Tulang Bawang Barat resmi merilis Siberugo. Platform ini mengintegrasikan seluruh OPD dalam pelayanan satu pintu secara digital.',
                'featured_image' => '',
                'category_id' => $catNews->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Panduan Mudah Mengurus Kartu Keluarga Online',
                'slug' => 'panduan-mudah-mengurus-kartu-keluarga-online',
                'excerpt' => 'Berikut adalah langkah-langkah mudah memperbarui data Kartu Keluarga secara daring di portal Siberugo.',
                'content' => 'Bagi Anda yang baru melangsungkan pernikahan atau memiliki anggota keluarga baru, silakan mendaftar di Siberugo, pilih Layanan Kependudukan, unggah berkas prasyarat, lalu tunggu konfirmasi verifikasi dari petugas.',
                'featured_image' => '',
                'category_id' => $catLayanan->id,
                'author_id' => $author?->id,
                'status' => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($articles as $article) {
            CmsArticle::updateOrCreate(['slug' => $article['slug']], $article);
        }

        // 11. FAQs
        $faqs = [
            [
                'question' => 'Apakah layanan di portal Siberugo dipungut biaya?',
                'answer' => 'Seluruh pelayanan publik, kependudukan, dan bantuan sosial di Siberugo adalah 100% gratis tanpa pungutan liar.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama proses verifikasi dokumen administrasi?',
                'answer' => 'Proses verifikasi bervariasi bergantung jenis layanan, umumnya membutuhkan waktu 1-3 hari kerja sejak berkas dinyatakan lengkap.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana jika berkas pengajuan saya ditolak?',
                'answer' => 'Anda akan mendapatkan pemberitahuan alasan penolakan dan dapat mengajukan ulang setelah melengkapi berkas yang kurang atau tidak sesuai.',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            CmsFaq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
