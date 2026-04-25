<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCitizenRequest extends FormRequest
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
            'nik' => ['required', 'string', 'size:16', 'unique:citizens,nik'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date_format:Y-m-d'],
            'alamat_desa' => ['required', 'string'],
            'kontak' => ['nullable', 'string', 'max:20'],
            'desa_id' => ['required', 'integer'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // For OperatorDesa, automatically set desa_id to their own desa_id
        if ($this->user()->role->slug === 'operatordesa') {
            $this->merge([
                'desa_id' => $this->user()->desa_id,
            ]);
        }
    }
}
