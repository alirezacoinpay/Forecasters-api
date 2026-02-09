<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Feed\FeedsRequest;
use App\Http\Resources\Client\PredictionResource;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use App\Repositories\UserSearchHistory\UserSearchHistoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class FeedController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected PredictionRepositoryInterface $repository,
        protected UserSearchHistoryRepositoryInterface $userSearchHistoryRepository,
    ) {
        $this->userId = auth()->user()?->getAuthIdentifier() ?? null;
    }


    public function feedPagePredictions(FeedsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $predictions = $this->repository->userFeedPredictions($this->userId, $validated);

        if (count($predictions) > 0) {
            $this->userSearchHistoryRepository->storeFromFeed(
                userId: $this->userId,
                params: $validated
            );
        }

        return $this->success(PredictionResource::collection($predictions));
    }

}
