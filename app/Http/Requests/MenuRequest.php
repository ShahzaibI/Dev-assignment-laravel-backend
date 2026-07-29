<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'parent_id'  => ['nullable', 'exists:menus,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Menu name is required.',
            'name.string'      => 'Menu name must be a string.',
            'name.max'         => 'Menu name may not exceed 255 characters.',
            'parent_id.exists' => 'The selected parent menu does not exist.',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min'     => 'Sort order must be 0 or greater.',
        ];
    }
}
