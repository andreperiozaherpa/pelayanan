<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CitizenFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasPermission('citizens.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $nikRule = ['required', 'string', 'size:16'];

        if ($this->isMethod('post')) {
            $nikRule[] = 'unique:citizens,nik';
        } else {
            // Because our primary key is a string 'nik', the route param might be the model instance or the string NIK depending on binding.
            $citizen = $this->route('citizen');
            $nikRule[] = Rule::unique('citizens', 'nik')->ignore($citizen);
        }

        return [
            'nik' => $nikRule,
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'alamat_desa' => ['required', 'string'],
            'kontak' => ['nullable', 'string', 'max:20'],

            // Poverty Status nested inputs
            'has_poverty_record' => ['nullable', 'boolean'],
            'poverty_status' => ['required_if:has_poverty_record,1', 'nullable', 'string', 'in:ACTIVE,EXPIRED,PENDING_REVIEW'],
            'income_range' => ['required_if:has_poverty_record,1', 'nullable', 'string', 'max:255'],
            'valid_from' => ['required_if:has_poverty_record,1', 'nullable', 'date'],
            'source' => ['required_if:has_poverty_record,1', 'nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 karakter.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'alamat_desa.required' => 'Alamat lengkap wajib diisi.',
            'poverty_status.required_if' => 'Status kemiskinan harus dipilih jika data kemiskinan disertakan.',
            'income_range.required_if' => 'Rentang pendapatan wajib diisi.',
            'valid_from.required_if' => 'Tanggal awal berkas wajib diisi.',
            'valid_from.date' => 'Format tanggal berkas tidak valid.',
            'source.required_if' => 'Sumber basis data wajib diisi.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Checkboxes in browsers may send 'on', convert to boolean-like 1 or 0
        if ($this->has('has_poverty_record')) {
            $this->merge([
                'has_poverty_record' => $this->input('has_poverty_record') === 'on' || $this->input('has_poverty_record') == 1,
            ]);
        } else {
            $this->merge([
                'has_poverty_record' => false,
            ]);
        }
    }
}
