<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Search\SearchRequest;
use App\Http\Resources\Client\QuestionResource;
use App\Repositories\Question\QuestionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected QuestionRepositoryInterface $repository,
    ) {
        $this->userId = auth()->user()?->getAuthIdentifier() ?? null;
    }


    public function searchHistory(): JsonResponse
    {
        //TODO

        return $this->success([]);
    }


    public function search(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $questions = $this->repository->userFeedQuestions($this->userId, $validated);

        return $this->success(QuestionResource::collection($questions));
    }

}
