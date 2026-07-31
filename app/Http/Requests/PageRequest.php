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
            'title_ar'     => ['nullable', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', Rule::unique('pages', 'slug')->ignore($pageId)->whereNull('deleted_at')],
            'body'         => ['required', 'string'],
            'body_ar'      => ['nullable', 'string'],
            'cover_image'  => ['nullable', 'image', 'max:2048'],
            'status'       => ['required', Rule::in(['draft', 'scheduled', 'published'])],
            'publish_date' => ['nullable', 'date'],
            'menu_id'      => ['required', 'exists:menus,id', Rule::unique('pages', 'menu_id')->ignore($pageId)->whereNull('deleted_at')],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        => 'Page title is required.',
            'title.max'             => 'Page title may not exceed 255 characters.',
            'slug.unique'           => 'This slug is already in use by another page.',
            'body.required'         => 'Page body content is required.',
            'cover_image.image'     => 'Cover image must be a valid image file (jpeg, png, gif, etc.).',
            'cover_image.max'       => 'Cover image may not exceed 2MB.',
            'status.required'       => 'Page status is required.',
            'status.in'             => 'Status must be draft, scheduled, or published.',
            'publish_date.date'     => 'Publish date must be a valid date.',
            'menu_id.required'      => 'A menu must be assigned to this page.',
            'menu_id.exists'        => 'The selected menu does not exist.',
            'menu_id.unique'        => 'This menu is already assigned to another page.',
        ];
    }
}
