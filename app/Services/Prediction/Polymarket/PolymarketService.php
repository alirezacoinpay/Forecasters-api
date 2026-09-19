<?php

namespace App\Services\Prediction\Polymarket;

use Illuminate\Support\Facades\Http;

class PolymarketService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.polymarket.base_url');
    }

    public function getSeries(array $queryParams = []): array
    {
        return $this->get('series', $queryParams);
    }

    public function getEvents(array $queryParams = []): array
    {
        return $this->get('events', $queryParams);
    }

    public function getMarkets(array $queryParams = []): array
    {
        return $this->get('markets', $queryParams);
    }

    public function getTags(array $queryParams = []): array
    {
        return $this->get('tags', $queryParams);
    }

    public function getTagBySlug(string $slug): array
    {
        return $this->get("tags/slug/{$slug}");
    }

    public function getRelatedTags(string|int $tagId): array
    {
        return $this->get("tags/{$tagId}/related-tags/tags");
    }

    protected function get(string $endpoint, array $queryParams = []): array
    {
        $response = Http::baseUrl($this->baseUrl)
            ->timeout(15)
            ->acceptJson()
            ->get($endpoint, $queryParams);

        return $response->throw()->json();
    }
}
