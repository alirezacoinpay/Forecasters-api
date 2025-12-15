<?php

namespace App\Http\Resources\Client;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionOptionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'question_id' => $this->question_id,
            'is_true' => $this->is_true,
            'userPredictionsCount' => $this->whenCounted('userPredictions'),
            'userPredictions' => UserPredictionResource::collection($this->whenLoaded('userPredictions')),
            'question' => new QuestionResource($this->whenLoaded('question')),
        ];
    }
}
