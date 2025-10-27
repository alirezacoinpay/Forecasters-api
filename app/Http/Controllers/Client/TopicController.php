<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Topics\AllTopicsRequest;
use App\Http\Resources\Client\TopicResource;
use App\Repositories\Topic\TopicRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    public function __construct(
        protected TopicRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $topic = $this->repository->findById($id);

        return $topic
            ? $this->success(new TopicResource($topic))
            : $this->error('api.not_found.topic', [], 404);
    }

    public function index(AllTopicsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $topics = $this->repository->all($validated);

        return $this->success($topics);
    }
}
