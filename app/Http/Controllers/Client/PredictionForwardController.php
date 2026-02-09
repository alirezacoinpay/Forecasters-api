<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PredictionForwards\AddPredictionForwardRequest;
use App\Http\Resources\Client\PredictionForwardResource;
use App\Repositories\PredictionForward\PredictionForwardRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PredictionForwardController extends Controller
{
    public function __construct(
        protected PredictionForwardRepositoryInterface $repository,
    ) {}

    public function store(AddPredictionForwardRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $predictionForward = $this->repository->create($validated);

        return $this->success(new PredictionForwardResource($predictionForward), 'api.created.predictionForward');
    }
}
