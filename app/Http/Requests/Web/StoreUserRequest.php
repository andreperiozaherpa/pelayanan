<?php

namespace App\Http\Requests\Web;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('users.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role_id' => ['required', 'exists:roles,id'],
            'district_id' => [
                Rule::requiredIf(function () {
                    $role = Role::find($this->role_id);

                    return $role && $role->slug === 'operatordesa' && empty($this->desa_id);
                }),
                'nullable',
                'exists:districts,id',
            ],
            'desa_id' => [
                'nullable',
                'exists:villages,id',
            ],
            'opd_id' => [
                Rule::requiredIf(function () {
                    $role = Role::find($this->role_id);

                    return $role && $role->slug === 'operatoropd';
                }),
                'nullable',
                'exists:opds,id',
            ],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'district_id.required' => 'Wilayah Kecamatan wajib dipilih untuk role Operator Desa.',
            'district_id.required_if' => 'Wilayah Kecamatan wajib dipilih untuk role Operator Desa.',
            'opd_id.required' => 'OPD wajib dipilih untuk role Operator OPD.',
            'opd_id.required_if' => 'OPD wajib dipilih untuk role Operator OPD.',
        ];
    }
}
