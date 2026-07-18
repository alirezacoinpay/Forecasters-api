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
            'avatar' => $this->whenLoaded('userProfile', $this->userProfile->avatar),
            'userPredictionsCount' => $this->whenCounted('userPredictions', $this->userPredictionsCount),
            'userPredictions' => UserPredictionResource::collection($this->whenLoaded('userPredictions')),
        ];
    }
}
