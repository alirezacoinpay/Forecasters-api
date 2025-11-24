<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\QuestionForwards\AddQuestionForwardRequest;
use App\Http\Resources\QuestionForwardResource;
use App\Repositories\QuestionForward\QuestionForwardRepositoryInterface;
use Illuminate\Http\JsonResponse;

class QuestionForwardController extends Controller
{
    public function __construct(
        protected QuestionForwardRepositoryInterface $repository,
    ) {}

    public function store(AddQuestionForwardRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $questionForward = $this->repository->create($validated);

        return $this->success(new QuestionForwardResource($questionForward), 'api.created.questionForward');
    }
}
