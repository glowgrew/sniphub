<?php

namespace App\Http\Resources\api\v1\Snippet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SnippetCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
