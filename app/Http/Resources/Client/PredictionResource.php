<?php

namespace App\Http\Resources\Client;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PredictionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'category_id' => $this->category_id,
            'topic_id' => $this->topic_id,
            'user_id' => $this->user_id,
            'closes_at' => $this->closes_at,
            'starts_at' => $this->starts_at,
            'resolve_at' => $this->resolve_at,
            'time_past' => DateHelper::shortTimeAgo($this->created_at),
            'userPredictionsCount' => $this->whenCounted('userPredictionsCount'),
            'commentsCount' => $this->whenCounted('commentsCount'),
            'user' => new UserResource($this->whenLoaded('user')),
            'predictionForwardCount' => $this->whenCounted('predictionForwards'),
            'predictionLikes' => $this->whenCounted('predictionLikes'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'userPredictions' => CommentResource::collection($this->whenLoaded('userPredictions')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'predictionOptions' => PredictionOptionResource::collection($this->whenLoaded('predictionOptions')),
        ];
    }
}
