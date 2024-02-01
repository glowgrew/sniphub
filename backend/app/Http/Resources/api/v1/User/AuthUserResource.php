<?php

namespace App\Http\Resources\api\v1\User;

use App\Http\Resources\api\v1\Snippet\SnippetCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'views' => $this->views,
            'created_at' => $this->created_at,
            'snippets' => new SnippetCollection($this->snippets),
        ];
    }
}
