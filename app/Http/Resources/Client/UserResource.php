<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'username' => $this->username,
            'mobile' => $this->mobile,
            'userPredictionsCount' => $this->whenCounted('userPredictions', $this->userPredictionsCount),
            'userPredictions' => $this->whenLoaded('userPredictions', UserPredictionResource::collection($this->userPredictions))
        ];
    }
}
