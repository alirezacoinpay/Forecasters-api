<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSearchHistoryResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'searchable_type' => $this->searchable_type,
            'searchable_id' => $this->searchable_id,
            'search_text' => $this->search_text,
            'searchable' => $this->whenLoaded('searchable'),
        ];
    }
}
