<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPredictionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'prediction_option_id' => $this->prediction_option_id,
            'percentage' => $this->percentage,
            'likesCount' => $this->whenCounted('predictionLikes'),
            'isLiked' => $this->whenLoaded('myPredictionLike', function () {
                return $this->myPredictionLike !== null;
            }),
            'prediction' => new PredictionResource($this->whenLoaded('prediction')),
            'predictionOption' => new PredictionOptionResource($this->whenLoaded('predictionOption')),
        ];
    }
}
