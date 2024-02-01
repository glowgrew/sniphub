<?php

namespace App\Http\Resources\api\v1\Snippet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SnippetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'category' => $this->category
                ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ]
                : null,
            'userId' => $this->user_id,
            'views' => $this->views,
            'burnAfterRead' => $this->burn_after_read,
            'isPublic' => $this->is_public,
            'uniqueId' => $this->unique_id,
            'expirationTime' => $this->expiration_time,
            'createdAt' => $this->created_at,
        ];
    }
}
