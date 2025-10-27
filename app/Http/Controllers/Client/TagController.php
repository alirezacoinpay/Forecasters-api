<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Tags\AllTagsRequest;
use App\Http\Resources\Client\TagResource;
use App\Repositories\Tag\TagRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __construct(
        protected TagRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $tag = $this->repository->findById($id);

        return $tag
            ? $this->success(new TagResource($tag))
            : $this->error('api.not_found.tag', [], 404);
    }

    public function index(AllTagsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $tags = $this->repository->all($validated);

        return $this->success($tags);
    }
}
