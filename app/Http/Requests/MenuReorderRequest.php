<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuReorderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'items'              => ['required', 'array'],
            'items.*.id'         => ['required', 'exists:menus,id'],
            'items.*.sort_order' => ['required', 'integer'],
            'items.*.parent_id'  => ['nullable', 'exists:menus,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'              => 'A list of menu items is required.',
            'items.array'                 => 'Menu items must be provided as an array.',
            'items.*.id.required'         => 'Each menu item must have an ID.',
            'items.*.id.exists'           => 'One or more menu item IDs do not exist.',
            'items.*.sort_order.required' => 'Each menu item must have a sort order.',
            'items.*.sort_order.integer'  => 'Sort order must be a whole number.',
            'items.*.parent_id.exists'    => 'The specified parent menu does not exist.',
        ];
    }
}
