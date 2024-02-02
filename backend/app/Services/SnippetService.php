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

    public function createSnippet($data): SnippetResource|JsonResponse|array
    {
        $data = $this->camelToSnakeService->camelToSnake($data);
        if (!array_key_exists('user_id', $data) && array_key_exists('is_public', $data)) {
            return response()->json(['message' => 'To create a private paste, you must be logged in'], 403);
        }

        $hashid = new Hashids('', 8);
        $data['unique_id'] = $hashid->encode(random_int(0, 100000));
        $snippet = Snippet::query()->create($data);
        return new SnippetResource($snippet);
    }

    public function showSnippet($request, $unique_id): JsonResponse|SnippetResource
    {
        $snippet = Snippet::query()->where('unique_id', $unique_id)->firstOrFail();

        $password = $request->input('password');

        if ($snippet->password && $snippet->password !== $password ) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        if (!$snippet || (!$snippet->is_public && $snippet->user_id !== auth('sanctum')->id())) {
            return response()->json(['message' => 'Paste not found or private'], 404);
        }

        if ($snippet->burn_after_read) {
            $snippet->delete();
        }

        $snippet->increment('views');

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