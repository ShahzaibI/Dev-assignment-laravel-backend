<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'name_ar'    => $this->name_ar,
            'parent_id'  => $this->parent_id,
            'sort_order' => $this->sort_order,
            'children'   => MenuResource::collection($this->whenLoaded('children')),
            'pages'      => $this->whenLoaded('pages', fn() => $this->pages->map(fn($p) => [
                'id'          => $p->id,
                'title'       => $p->title,
                'title_ar'    => $p->title_ar,
                'slug'        => $p->slug,
                'cover_image' => $p->cover_image ? asset('storage/' . $p->cover_image) : null,
                'publish_date'=> $p->publish_date?->toISOString(),
            ])->values()),
        ];
    }
}
