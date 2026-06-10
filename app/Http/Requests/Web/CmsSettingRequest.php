<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CmsSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('cms.settings.edit');
    }

    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string'],
        ];
    }
}
