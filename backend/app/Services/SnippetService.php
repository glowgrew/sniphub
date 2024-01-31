<?php

namespace App\Services;

use App\Http\Resources\api\v1\Snippet\SnippetResource;
use App\Models\Snippet;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class SnippetService
{
    protected CamelToSnakeService $camelToSnakeService;

    public function __construct(CamelToSnakeService $camelToSnakeService)
    {
        $this->camelToSnakeService = $camelToSnakeService;
    }

    public function createSnippet($data): SnippetResource
    {
        $data = $this->camelToSnakeService->camelToSnake($data);

        $hashid = new Hashids('', 8);
        $data['unique_id'] = $hashid->encode(random_int(0, 100000));
        return new SnippetResource(Snippet::query()->create($data));
    }

    public function showSnippet($unique_id): JsonResponse|SnippetResource
    {
        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        if (!$snippet || !$snippet->is_public) {
            return response()->json(['message' => 'Paste not found or private'], 404);
        }
        return new SnippetResource($snippet);
    }

    public function updateSnippet($data, $unique_id): SnippetResource
    {
        $data = $this->camelToSnakeService->camelToSnake($data);

        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        $snippet->update($data);
        return new SnippetResource($snippet);
    }

    public function deleteSnippet($unique_id): JsonResponse
    {
        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();
        $snippet->delete();
        return response()->json(['message' => 'Snippet deleted successfully'], 200);
    }

    public function validate($request, $unique_id, $method)
    {
        if ($method === 'update') {
            if ($request->user()->cannot('update', Snippet::query()->where('unique_id', $unique_id)->firstOrFail())) {
                return false;
            }
            return true;
        }
        if ($method === 'delete') {
            if ($request->user()->cannot('delete', Snippet::query()->where('unique_id', $unique_id)->firstOrFail())) {
                return false;
            }
            return true;
        }
    }
}