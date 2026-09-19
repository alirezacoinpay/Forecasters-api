<?php

namespace App\Jobs;

use App\Models\Prediction;
use App\Services\Prediction\Polymarket\PolymarketService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FetchThirdPartyPrediction implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected PolymarketService $polymarketService
    )
    {

    }
//    public function handle(): void
//    {
//
//        $targetTag = $this->polymarketService->getTagBySlug($this->getTargetTagSlug());
//        $relatedTags = $this->polymarketService->getRelatedTags($targetTag['id']);
//
//        $response = $this->polymarketService->getMarkets([
//            'limit' => 2,
//            'tag_slug' => 'iran',
//            'order' => 'startDate',
//            'ascending' => false,
//            'after_cursor' => $offset,
//            'start_date_min' => Carbon::today()->subDays(1)->toDateString(),
//        ]);
//
//        // Polymarket returns the list inside the 'events' key
//        $events = $response['events'] ?? $response ?? [];
//
//        if (empty($events)) {
//            return;
//        }
//
//        Cache::tags('polymarket_events')->put('offset', $offset + count($events));
//
//        $eventCollection = Collection::make($events);
//
//        // 1. Get all event IDs from the API response
//        $apiIds = $eventCollection->pluck('id')->all();
//
//        // 2. Query your Prediction table for existing event IDs
//        $existingIds = Prediction::whereIn('event_id', $apiIds)
//            ->pluck('event_id')
//            ->all();
//
//        // 3. Filter out existing records and map new ones for insertion
//        $newRecords = $eventCollection
//            ->reject(fn($event) => in_array($event['id'], $existingIds))
//            ->map(function ($event) {
//                return [
//                    'event_id' => $event['id'],
//                    'slug' => $event['slug'],
//                    'title' => $event['title'],
//                    'description' => $event['description'] ?? null,
//                    'volume' => (float)($event['volume'] ?? 0),
//                    'active' => (bool)($event['active'] ?? false),
//                    'start_date' => isset($event['startDate']) ? Carbon::parse($event['startDate']) : null,
//                    'end_date' => isset($event['endDate']) ? Carbon::parse($event['endDate']) : null,
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ];
//            })
//            ->values()
//            ->all();
//
//        // 4. Batch insert all new predictions in one query
//        if (!empty($newRecords)) {
//            Prediction::insert($newRecords);
//        }
//    }

    private function getTargetTagSlug(): string
    {
        return 'iran';
    }
}
