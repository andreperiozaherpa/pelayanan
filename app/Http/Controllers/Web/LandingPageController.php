<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CmsArticle;
use App\Models\CmsCategory;
use App\Models\CmsComplaint;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use App\Models\CmsTeam;
use App\Services\LandingPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        private readonly LandingPageService $landingPageService
    ) {}

    public function index(): View
    {
        $data = $this->landingPageService->getPageData();

        return view('pages.landing', $data);
    }

    public function showPage(string $section, string $slug): View
    {
        $menus = Cache::remember(
            'landing.menus',
            3600,
            fn () => CmsMenu::with('children')
                ->where('parent_id', null)
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
        );

        $settings = Cache::remember(
            'landing.settings',
            3600,
            fn () => CmsSetting::pluck('value', 'key')->toArray()
        );

        // 0. Special case: Pengaduan
        if ($slug === 'pengaduan') {
            $page = (object) [
                'title' => 'Pengaduan Masyarakat',
                'seo' => null,
            ];

            return view('pages.complaint-form', compact('page', 'menus', 'settings'));
        }

        // 1. Special case: Struktur Organisasi
        if ($slug === 'struktur-organisasi') {
            $team = CmsTeam::where('is_active', true)->orderBy('order')->get();
            $page = (object) [
                'title' => 'Struktur Organisasi & Pimpinan',
                'seo' => null,
            ];

            return view('pages.struktur-organisasi', compact('page', 'menus', 'settings', 'team'));
        }

        // 2. Check if it's an article detail
        $article = CmsArticle::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if ($article) {
            $page = (object) [
                'title' => $article->title,
                'seo' => $article->seo ?? null,
            ];
            // Get related articles
            $relatedArticles = CmsArticle::where('id', '!=', $article->id)
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->get();

            return view('pages.article-detail', compact('article', 'page', 'menus', 'settings', 'relatedArticles'));
        }

        // 3. Check if it's a category listing (e.g. berita, pengumuman)
        $category = CmsCategory::where('slug', $slug)->first();
        if ($category) {
            $query = CmsArticle::where('category_id', $category->id)
                ->where('status', 'published');

            if (request('q')) {
                $search = request('q');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            $articles = $query->latest()->paginate(9)->withQueryString();

            $page = (object) [
                'title' => $category->name,
                'seo' => null,
            ];

            return view('pages.article-index', compact('category', 'articles', 'page', 'menus', 'settings'));
        }

        // 4. Otherwise, fetch standard static page
        $page = CmsPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('pages.show', compact('page', 'menus', 'settings'));
    }

    public function submitComplaint(Request $request): RedirectResponse
    {
        $request->validate([
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib diisi.',
        ]);

        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (! $recaptchaResponse->json('success')) {
            return back()->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA tidak valid. Silakan coba lagi.'])->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('complaints', 'public');
            $validated['attachment'] = $path;
        }

        CmsComplaint::create($validated);

        return back()->with('success', 'Pengaduan Anda telah berhasil dikirim dan akan segera diproses oleh admin. Terima kasih atas laporan Anda.');
    }
}
