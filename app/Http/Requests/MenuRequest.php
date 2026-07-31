<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MenuRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'name_ar'    => ['nullable', 'string', 'max:255'],
            'parent_id'  => ['nullable', 'exists:menus,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $parentId = $this->input('parent_id');

                if (!$parentId) return;

                // The menu being updated (null on create)
                $menuId = $this->route('menu')?->id;

                // A menu that already has children cannot become a child
                if ($menuId) {
                    $hasChildren = Menu::where('parent_id', $menuId)->exists();
                    if ($hasChildren) {
                        $validator->errors()->add('parent_id', 'A menu that has children cannot be nested under another menu.');
                    }
                }

                // The chosen parent must itself be a top-level menu (no nesting beyond 1 level)
                $parent = Menu::find($parentId);
                if ($parent && $parent->parent_id !== null) {
                    $validator->errors()->add('parent_id', 'Cannot nest more than one level deep.');
                }
            },
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
