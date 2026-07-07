<?php

namespace App\Http\Requests\Web;

use App\Models\CmsMenu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CmsMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return $this->user()->hasPermission('cms.menus.create');
        }

        return $this->user()->hasPermission('cms.menus.edit');
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->cms_page_id) && $this->filled('title') && Str::slug($this->title) === 'struktur-organisasi') {
            $parentId = $this->filled('parent_id') ? (int) $this->parent_id : null;
            $url = CmsMenu::generateUrlForPage($parentId, 'struktur-organisasi');
            $this->merge([
                'url' => $url,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:cms_menus,id'],
            'cms_page_id' => ['nullable', 'integer', 'exists:cms_pages,id'],
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required_without:cms_page_id', 'nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'target' => ['required', 'in:_self,_blank'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ];
    }
}
