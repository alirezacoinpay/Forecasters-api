<?php

namespace App\Http\Resources\Client;

use App\Helpers\DateHelper;
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
            'timePast' => DateHelper::shortTimeAgo($this->created_at),
            'created_at' => $this->created_at->format('F j Y'),
        ];
    }
}
