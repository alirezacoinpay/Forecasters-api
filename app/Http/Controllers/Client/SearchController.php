<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Search\SearchRequest;
use App\Http\Resources\Client\PredictionResource;
use App\Http\Resources\Client\UserSearchHistoryResource;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use App\Repositories\UserSearchHistory\UserSearchHistoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected UserSearchHistoryRepositoryInterface $repository,
    ) {
        $this->userId = auth()->user()?->getAuthIdentifier() ?? null;
    }



    public function search(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['paginate'] = 4;
        $search = $this->repository->userSearchPredictions($this->userId, $validated);

        return $this->success(UserSearchHistoryResource::collection($search));
    }

}
