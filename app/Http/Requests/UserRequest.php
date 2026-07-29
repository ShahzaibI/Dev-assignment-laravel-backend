<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$#!%*?&])[A-Za-z\d@$#!%*?&]+$/'],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Full name is required.',
            'name.max'          => 'Name may not exceed 255 characters.',
            'email.required'    => 'Email address is required.',
            'email.email'       => 'Please provide a valid email address.',
            'email.unique'      => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.regex'    => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$#!%*?&).',
            'role.required'     => 'A role must be assigned to the user.',
            'role.exists'       => 'The selected role does not exist.',
        ];
    }
}
