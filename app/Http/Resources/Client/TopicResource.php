<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'icon' => $this->icon,
            'status' => $this->status,
        ];
    }
}
