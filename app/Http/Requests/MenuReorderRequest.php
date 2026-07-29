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
}
