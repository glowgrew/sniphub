<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSnippetRequest;
use App\Http\Requests\UpdateSnippetRequest;
use App\Http\Resources\api\v1\Snippet\SnippetCollection;
use App\Http\Resources\api\v1\Snippet\SnippetResource;
use App\Models\User;
use App\Services\SnippetService;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    protected SnippetService $snippetService;

    public function __construct(SnippetService $snippetService)
    {
        $this->snippetService = $snippetService;
    }

    public function index(User $user)
    {
        return new SnippetCollection($user->snippets);
    }

    public function indexOfAuthUser()
    {
        $user = auth('sanctum')->user();
        return new SnippetCollection($user->snippets);
    }

    public function store(StoreSnippetRequest $request)
    {
        $data = $request->validated();
        if (auth('sanctum')->check()) {
            $data['user_id'] = auth('sanctum')->user()->id;
        }
        return new SnippetResource($this->snippetService->createSnippet($data));
    }

    public function show($unique_id)
    {
        return new SnippetResource($this->snippetService->showSnippet($unique_id));
    }

    public function update(UpdateSnippetRequest $request, $unique_id)
    {
        if (!$this->snippetService->validate($request, $unique_id, 'update')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validated();
        return new SnippetResource($this->snippetService->updateSnippet($data, $unique_id));
    }

    public function destroy(Request $request, $unique_id)
    {
        if (!$this->snippetService->validate($request, $unique_id, 'delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->snippetService->deleteSnippet($unique_id);
    }
}
