<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'body'         => $this->body,
            'cover_image'  => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'status'       => $this->status,
            'publish_date' => $this->publish_date?->toISOString(),
            'menu'         => $this->whenLoaded('menu', fn() => ['id' => $this->menu->id, 'name' => $this->menu->name]),
            'created_by'   => $this->whenLoaded('creator', fn() => ['id' => $this->creator->id, 'name' => $this->creator->name]),
            'updated_by'   => $this->whenLoaded('updater', fn() => $this->updater ? ['id' => $this->updater->id, 'name' => $this->updater->name] : null),
            'deleted_at'   => $this->deleted_at?->toISOString(),
            'created_at'   => $this->created_at->toISOString(),
            'updated_at'   => $this->updated_at->toISOString(),
        ];
    }
}
