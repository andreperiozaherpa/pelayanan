<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CmsPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return $this->user()->hasPermission('cms.pages.create');
        }

        return $this->user()->hasPermission('cms.pages.edit');
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'status' => $this->boolean('is_active') ? 'published' : 'draft',
            ]);
        } elseif (! $this->has('status')) {
            $this->merge([
                'status' => 'draft',
            ]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('cms_page')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:cms_pages,slug,'.($id ?? 'NULL')],
            'content' => ['required', 'string'],
            'template' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],

            // Polymorphic SEO fields
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
