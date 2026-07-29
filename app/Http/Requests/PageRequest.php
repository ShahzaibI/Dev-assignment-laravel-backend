<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', Rule::unique('pages', 'slug')->ignore($pageId)->whereNull('deleted_at')],
            'body'         => ['required', 'string'],
            'cover_image'  => ['nullable', 'image', 'max:2048'],
            'status'       => ['required', Rule::in(['draft', 'published'])],
            'publish_date' => ['nullable', 'date'],
            'menu_id'      => ['nullable', 'exists:menus,id'],
        ];
    }
}
