<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePovertyRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('poverty.update');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'citizen_nik' => ['required', 'string', 'exists:citizens,nik'],
            'status' => ['required', 'string', 'in:ACTIVE,EXPIRED,PENDING'],
            'income_range' => ['required', 'string'],
            'valid_from' => ['required', 'date_format:Y-m-d'],
            'valid_until' => ['required', 'date_format:Y-m-d', 'after:valid_from'],
            'source' => ['nullable', 'string', 'max:255'],
        ];
    }
}
