<?php

namespace App\Http\Resources\Client;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PredictionOptionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'prediction_id' => $this->prediction_id,
            'is_true' => $this->is_true,
            'userPredictionsCount' => $this->whenCounted('userPredictions'),
            'userPredictions' => UserPredictionResource::collection($this->whenLoaded('userPredictions')),
            'prediction' => new PredictionResource($this->whenLoaded('prediction')),
            'myPrediction' => new PredictionResource($this->whenLoaded('myPrediction')),
        ];
    }
}
