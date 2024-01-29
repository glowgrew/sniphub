<?php

namespace App\Services;

use App\Http\Resources\api\v1\Snippet\SnippetResource;
use App\Models\Snippet;
use Hashids\Hashids;

class SnippetService
{
    public function createSnippet($data)
    {
        $hashid = new Hashids('', 8);
        $data['unique_id'] = $hashid->encode(random_int(0, 100000));
        return Snippet::query()->create($data);
    }

    public function showSnippet($unique_id)
    {
        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        if (!$snippet) {
            return response()->json(['message' => 'Paste not found'], 404);
        }
        return $snippet;
    }
}