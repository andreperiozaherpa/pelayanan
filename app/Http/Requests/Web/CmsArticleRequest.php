<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CmsArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return $this->user()->hasPermission('cms.articles.create');
        }

        return $this->user()->hasPermission('cms.articles.edit');
    }

    public function rules(): array
    {
        $id = $this->route('cms_article')?->id;

        return [
            'category_id' => ['nullable', 'exists:cms_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:cms_articles,slug,'.($id ?? 'NULL')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // Max 5MB
            'status' => ['required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'required_if:status,scheduled', 'date'],

            // Polymorphic SEO fields
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
