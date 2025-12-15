<?php

namespace App\Http\Resources\Client;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            'question_id' => $this->question_id,
            'text' => $this->text,
            'file' => $this->file,
            'time_past' => DateHelper::shortTimeAgo($this->created_at),
            'question' => new QuestionResource($this->whenLoaded('question')),
            'user' => new UserResource($this->whenLoaded('user')),
            'parent' => new CommentResource($this->whenLoaded('parent')),
            'children' => CommentResource::collection($this->whenLoaded('children')),
            'childrenCount' => $this->whenCounted('children'),
            'likesCount' => $this->whenCounted('children'),
        ];
    }
}
