<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Role name is required.',
            'name.max'               => 'Role name may not exceed 255 characters.',
            'permissions.array'      => 'Permissions must be provided as an array.',
            'permissions.*.string'   => 'Each permission must be a string.',
            'permissions.*.exists'   => 'One or more selected permissions do not exist.',
        ];
    }
}
