<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Snippet\StoreSnippetRequest;
use App\Http\Requests\Snippet\UpdateSnippetRequest;
use App\Services\SnippetService;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    protected SnippetService $snippetService;

    public function __construct(SnippetService $snippetService)
    {
        $this->snippetService = $snippetService;
    }

    public function store(StoreSnippetRequest $request)
    {
        $data = $request->validated();
        if (auth('sanctum')->check()) {
            $data['user_id'] = auth('sanctum')->user()->id;
        }
        return $this->snippetService->createSnippet($data);
    }

    public function show(Request $request, $unique_id)
    {
        if (!$this->snippetService->checkExists($unique_id)) {
            return response()->json(['message' => 'Snippet not found'], 404);
        }

        return $this->snippetService->showSnippet($request, $unique_id);
    }

    public function update(UpdateSnippetRequest $request, $unique_id)
    {
        if (!$this->snippetService->checkExists($unique_id)) {
            return response()->json(['message' => 'Snippet not found'], 404);
        }
        if (!$this->snippetService->validate($request, $unique_id, 'update')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validated();
        return $this->snippetService->updateSnippet($data, $unique_id);
    }

    public function destroy(Request $request, $unique_id)
    {
        if (!$this->snippetService->checkExists($unique_id)) {
            return response()->json(['message' => 'Snippet not found'], 404);
        }
        if (!$this->snippetService->validate($request, $unique_id, 'delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->snippetService->deleteSnippet($unique_id);
    }
}
