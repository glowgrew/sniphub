<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSnippetRequest;
use App\Http\Requests\UpdateSnippetRequest;
use App\Http\Resources\api\v1\Snippet\SnippetResource;
use App\Models\Snippet;
use App\Services\SnippetService;
use Hashids\Hashids;

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

        return new SnippetResource($this->snippetService->createSnippet($data));
    }

    public function show($unique_id)
    {
        return new SnippetResource($this->snippetService->showSnippet($unique_id));
    }

    public function update(UpdateSnippetRequest $request, Snippet $snippet)
    {
        //
    }

    public function destroy(Snippet $snippet)
    {
        //
    }
}
