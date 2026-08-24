<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'login' => ['required_without:username', 'string'],
            'username' => ['required_without:login', 'string'],
            'password' => ['required', 'string'],
            'role' => ['sometimes', 'string', 'exists:roles,slug'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Email atau username wajib diisi.',
            'username.required' => 'Email atau username wajib diisi.',
            'role.exists' => 'Role yang dipilih tidak valid.',
        ];
    }
}
