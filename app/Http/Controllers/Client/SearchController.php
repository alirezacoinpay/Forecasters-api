<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Search\SearchRequest;
use App\Http\Resources\Client\PredictionResource;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected PredictionRepositoryInterface $repository,
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
        $predictions = $this->repository->userSearchPredictions($this->userId, $validated);

        return $this->success(PredictionResource::collection($predictions));
    }

}
