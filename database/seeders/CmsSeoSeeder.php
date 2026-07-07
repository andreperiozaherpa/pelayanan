<?php

namespace Database\Seeders;

use App\Models\CmsArticle;
use App\Models\CmsPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed SEO untuk CmsPage
        $pages = CmsPage::all();
        foreach ($pages as $page) {
            $page->seo()->updateOrCreate(
                [],
                [
                    'meta_title' => $page->title.' - DPMPTSP Kabupaten Tulang Bawang Barat',
                    'meta_description' => Str::limit(strip_tags($page->content), 150),
                    'meta_keywords' => 'dpmptsp tubaba, pelayanan publik, '.strtolower($page->title),
                    'canonical_url' => url('/profil/'.$page->slug),
                ]
            );
        }

        // 2. Seed SEO untuk CmsArticle
        $articles = CmsArticle::all();
        foreach ($articles as $article) {
            $article->seo()->updateOrCreate(
                [],
                [
                    'meta_title' => $article->title.' - Berita DPMPTSP Tubaba',
                    'meta_description' => $article->excerpt ?? Str::limit(strip_tags($article->content), 150),
                    'meta_keywords' => 'berita tubaba, dpmptsp berita, '.strtolower($article->title),
                    'canonical_url' => url('/berita/'.$article->slug),
                ]
            );
        }
    }
}
