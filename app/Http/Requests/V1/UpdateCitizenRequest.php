<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCitizenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('citizens.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nik' => ['sometimes', 'string', 'size:16', Rule::unique('citizens', 'nik')->ignore($this->citizen)],
            'nama_lengkap' => ['sometimes', 'string', 'max:255'],
            'tgl_lahir' => ['sometimes', 'date_format:Y-m-d'],
            'alamat_desa' => ['sometimes', 'string'],
            'kontak' => ['nullable', 'string', 'max:20'],
            'desa_id' => ['sometimes', 'integer'],
        ];
    }
}
