<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Predictions\AddPredictionsRequest;
use App\Http\Requests\Client\Predictions\AllPredictionCommentsRequest;
use App\Http\Resources\Client\CommentResource;
use App\Http\Resources\Client\PredictionResource;
use App\Models\PredictionOption;
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
    public function comments(AllPredictionCommentsRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $predictionComments = $this->repository->predictionComments($id, $validated);
        return $this->success(CommentResource::collection($predictionComments));
    }

    public function store(AddPredictionsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $prediction = $this->repository->create($validated);
        $predictionOptions = $validated['options'];

        // Add prediction_id to each option
        $predictionOptionsData = array_map(function($option) use ($prediction) {

            $optionData['prediction_id'] = $prediction->id;
            $optionData['title'] = $option;
            return $optionData;
        }, $predictionOptions);

        $this->repository->insertPredictionOptions($predictionOptionsData);
        $prediction->load('predictionOptions');
        return $prediction
            ? $this->success(new PredictionResource($prediction))
            : $this->error('api.not_found.prediction', [], 404);
    }

}
