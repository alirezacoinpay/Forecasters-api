<?php

namespace App\Http\Controllers\Client;

use App\Enums\ActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\PredictionResource;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use App\Repositories\UserPrediction\UserPredictionRepositoryInterface;
use App\Services\ActivityLogger\ActivityLogger;
use Illuminate\Http\JsonResponse;

class PredictionLikeController extends Controller
{
    public function __construct(
        protected UserPredictionRepositoryInterface $repository,
        protected PredictionRepositoryInterface $predictionRepository,
    ) {}

    public function toggle($id): JsonResponse
    {
        $userId = auth()->id();

        if (!$userId) {
            return $this->error('api.unauthorized', [], 401);
        }

        $prediction = $this->predictionRepository->findByIdLight($id);

        if (!$prediction) {
            return $this->error('api.not_found.prediction', [], 404);
        }

        $isLiked = $this->repository->togglePredictionLike($id, $userId);

        if ($isLiked === null) {
            return $this->error('api.error.like_toggle', [], 500);
        }

        ActivityLogger::log(
            $userId,
            $isLiked ? ActivityAction::PREDICTION_LIKE : ActivityAction::UNLIKE,
            $prediction
        );

        $prediction = $this->predictionRepository->findFeedPage($id);
        return $this->success([
            'prediction' => new PredictionResource($prediction),
        ]);
    }
}

