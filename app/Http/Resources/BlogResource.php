<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'image'    => $this->image ? asset('storage/' . $this->image) : null,
            'excerpt'      => $this->excerpt,
            'content'      => $this->content,
            'published_at' => $this->created_at->toDateString(),
        ];
    }
}
