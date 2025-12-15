<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\QuestionResource;
use App\Repositories\Question\QuestionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected QuestionRepositoryInterface $repository,
    ) {
        $this->userId = auth()->user()?->getAuthIdentifier() ?? null;
    }

    public function show($id): JsonResponse
    {
        $question = $this->repository->userFeedQuestion($id, $this->userId);

        return $question
            ? $this->success(new QuestionResource($question))
            : $this->error('api.not_found.question', [], 404);
    }

}
