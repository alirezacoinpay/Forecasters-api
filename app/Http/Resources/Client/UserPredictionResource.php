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
            'question_option_id' => $this->question_option_id,
            'percentage' => $this->percentage,
            'question' => new QuestionResource($this->whenLoaded('question')),
            'questionOption' => new QuestionOptionResource($this->whenLoaded('question')),
        ];
    }
}
