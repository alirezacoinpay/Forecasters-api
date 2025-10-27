<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Questions\AllQuestionsRequest;
use App\Http\Resources\Client\QuestionResource;
use App\Repositories\Question\QuestionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function __construct(
        protected QuestionRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $question = $this->repository->findFeedPage($id);

        return $question
            ? $this->success(new QuestionResource($question))
            : $this->error('api.not_found.question', [], 404);
    }

    public function index(AllQuestionsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $questions = $this->repository->allFeedPage($validated);

        return $this->success($questions);
    }

}
