<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CmsCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('cms.articles.create')
            || $this->user()->hasPermission('cms.articles.edit');
    }

    public function rules(): array
    {
        $id = $this->route('cms_category')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:cms_categories,slug,'.($id ?? 'NULL')],
            'parent_id' => ['nullable', 'exists:cms_categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
