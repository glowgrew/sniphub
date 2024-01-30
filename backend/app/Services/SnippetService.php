<?php

namespace App\Services;

use App\Models\Snippet;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SnippetService
{
    protected CamelToSnakeService $camelToSnakeService;

    public function __construct(CamelToSnakeService $camelToSnakeService)
    {
        $this->camelToSnakeService = $camelToSnakeService;
    }

    public function createSnippet($data)
    {
        $data = $this->camelToSnakeService->camelToSnake($data);

        $hashid = new Hashids('', 8);
        $data['unique_id'] = $hashid->encode(random_int(0, 100000));
        return Snippet::query()->create($data);
    }

    public function showSnippet($unique_id): Model|Builder|JsonResponse
    {
        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        if (!$snippet) {
            return response()->json(['message' => 'Paste not found'], 404);
        }
        return $snippet;
    }

    public function updateSnippet($data, $unique_id): Model|Builder
    {
        $data = $this->camelToSnakeService->camelToSnake($data);

        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        $snippet->update($data);
        return $snippet;
    }

    public function deleteSnippet($unique_id): JsonResponse
    {
        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        $snippet->delete();
        return response()->json(['message' => 'Snippet deleted successfully'], 200);
    }
}