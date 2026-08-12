<?php
namespace App\Services\Prediction;

use App\Enums\CategoryStatus;
use App\Enums\TopicStatus;
use App\Models\Category;
use App\Models\Prediction;
use App\Models\Tag;
use App\Models\Topic;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PredictionImporterService
{
    /**
     * Import multiple predictions from an array or a raw JSON string/payload.
     *
     * @param array|string $payload
     * @param string $language
     * @param string $region
     * @param int|null $userId
     * @return Collection<Prediction>
     */
    public function import(
        array|string $payload,
        string $language = 'English',
        string $region = 'Global',
        ?int $userId = null
    ): Collection {
        $predictionsData = $this->normalizePayload($payload);

        $savedPredictions = collect();

        foreach ($predictionsData as $item) {
            try {
                $saved = $this->importSingle($item, $language, $region, $userId);
                if ($saved) {
                    $savedPredictions->push($saved);
                }
            } catch (Exception $e) {
                Log::warning('PredictionImporterService: Failed to import single item.', [
                    'error' => $e->getMessage(),
                    'item'  => $item,
                ]);
            }
        }

        return $savedPredictions;
    }

    /**
     * Import a single structured prediction item array.
     */
    public function importSingle(
        array $structured,
        string $language = 'English',
        string $region = 'Global',
        ?int $userId = null
    ): Prediction {
        if (empty($structured['title'])) {
            throw new Exception('Invalid prediction item: Missing title.');
        }

        $rawTitle = trim($structured['title']);
        $normalizedTitle = mb_strtolower($rawTitle);
        $titleHash = hash('sha256', $normalizedTitle);

        if (Prediction::where('title_hash', $titleHash)->exists()) {
            throw new Exception("Duplicate prediction detected ('{$rawTitle}'). Skipping.");
        }

        return DB::transaction(function () use ($structured, $language, $region, $titleHash, $userId) {
            // 1. Resolve Category
            $categoryId = null;
            if (!empty($structured['category'])) {
                $category = Category::firstOrCreate(
                    ['title' => $structured['category']],
                    ['status' => CategoryStatus::ACTIVE]
                );
                $categoryId = $category->id;
            }

            // 2. Resolve Topic
            $topicId = null;
            if (!empty($structured['topic'])) {
                $topic = Topic::firstOrCreate(
                    ['title' => $structured['topic']],
                    ['status' => TopicStatus::ACTIVE, 'icon' => 'no-icon']
                );
                $topicId = $topic->id;
            }

            // 3. Create Prediction Record
            $prediction = Prediction::create([
                'title'       => $structured['title'],
                'text'        => null,
                'category_id' => $categoryId,
                'topic_id'    => $topicId,
                'user_id'     => $userId ?? Auth::id() ?? 1,
                'language'    => $language,
                'region'      => $region,
                'title_hash'  => $titleHash,
                'starts_at'   => now(),
                'closes_at'   => now()->addDays(30),
            ]);

            // 4. Create Prediction Options
            if (!empty($structured['options']) && is_array($structured['options'])) {
                foreach ($structured['options'] as $optionText) {
                    $prediction->predictionOptions()->create([
                        'title'   => $optionText,
                        'is_true' => false,
                    ]);
                }
            }

            // 5. Sync Tags
            if (!empty($structured['tags']) && is_array($structured['tags'])) {
                $tagIds = [];
                foreach ($structured['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['title' => $tagName],
                        ['color' => '#aq313e']
                    );
                    $tagIds[] = $tag->id;
                }
                $prediction->tags()->sync($tagIds);
            }

            return $prediction->load(['predictionOptions', 'category', 'topic', 'tags']);
        });
    }

    /**
     * Normalizes inputs (JSON strings, structured wrappers) into a flat array of prediction items.
     */
    protected function normalizePayload(array|string $payload): array
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true) ?? [];
        }

        if (isset($payload['predictions']) && is_array($payload['predictions'])) {
            return $payload['predictions'];
        }

        // If a single prediction array was passed directly
        if (isset($payload['title'])) {
            return [$payload];
        }

        return is_array($payload) ? $payload : [];
    }
}
