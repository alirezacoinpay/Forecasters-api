<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\PredictionResource;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PredictionController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected PredictionRepositoryInterface $repository,
    ) {
        $this->userId = auth()->user()?->getAuthIdentifier() ?? null;
    }

    public function show($id): JsonResponse
    {
        $prediction = $this->repository->userFeedPrediction($id, $this->userId);

        return $prediction
            ? $this->success(new PredictionResource($prediction))
            : $this->error('api.not_found.prediction', [], 404);
    }

}
