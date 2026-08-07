<?php

namespace App\Services\Prediction;

use App\Ai\Agents\PredictionGenerator;
use App\Ai\Data\GeneratePredictionRequest;
use App\Enums\CategoryStatus;
use App\Enums\TopicStatus;
use App\Models\Category;
use App\Models\Prediction;
use App\Models\Tag;
use App\Models\Topic;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PredictionAiService
{
    public function generateAndSaveOld(array $params = [], ?int $userId = null): Prediction
    {
        $request = new GeneratePredictionRequest(
            language: $params['language'] ?? 'English',
            region: $params['region'] ?? 'Global',
            topic: $params['topic'] ?? null,
            category: $params['category'] ?? null,
            additionalInstructions: $params['instructions'] ?? null,
        );

        $agent = new PredictionGenerator($request);
        $response = $agent->prompt('Generate a prediction based on the given context.');

        $structured = $response->structured ?? [];

        if (empty($structured)) {
            Log::error('PredictionService: Structured AI response was empty.', [
                'raw_response' => $response->text ?? null,
            ]);
            throw new Exception('Failed to generate structured AI response.');
        }

        return DB::transaction(function () use ($structured, $userId) {
            // 1. Category
            $categoryId = null;
            $categoryName = $structured['category'] ?? null;
            if ($categoryName) {
                $category = Category::firstOrCreate(
                    ['title' => $categoryName],
                    ['status' => CategoryStatus::ACTIVE]
                );
                $categoryId = $category->id;
            }

            // 2. Topic
            $topicId = null;
            $topicName = $structured['topic'] ?? null;
            if ($topicName) {
                $topic = Topic::firstOrCreate(
                    ['title' => $topicName],
                    ['status' => TopicStatus::ACTIVE]
                );
                $topicId = $topic->id;
            }

            // 3. Prediction Record
            $prediction = Prediction::create([
                'title'       => $structured['title'],
                'category_id' => $categoryId,
                'topic_id'    => $topicId,
                'user_id'     => $userId ?? Auth::id() ?? 1,
                'starts_at'   => now(),
            ]);

            // 4. Options
            if (!empty($structured['options']) && is_array($structured['options'])) {
                foreach ($structured['options'] as $optionText) {
                    $prediction->predictionOptions()->create([
                        'title'   => $optionText,
                        'is_true' => false,
                    ]);
                }
            }

            // 5. Tags
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

    public function generateAndSave(array $params = [], ?int $userId = null): Prediction
    {
        $language = $params['language'] ?? 'English';
        $region   = $params['region'] ?? 'Global';

        $request = new GeneratePredictionRequest(
            language: $language,
            region: $region,
            topic: $params['topic'] ?? null,
            category: $params['category'] ?? null,
            additionalInstructions: $params['instructions'] ?? null,
        );

        $agent = new PredictionGenerator($request);

        // Execute prompt with automatic retry for network hiccups
        $response = retry(2, function () use ($agent) {
            return $agent->prompt('Generate a prediction based on the given context.');
        }, 2000);

        $structured = $response->structured ?? [];

        if (empty($structured) || empty($structured['title'])) {
            throw new Exception('Failed to generate valid structured AI response.');
        }

        $rawTitle = trim($structured['title']);

        // 1. Language Script Guardrail (Rejects corrupted Latin tokens in Persian output)
        if ($language === 'Persian' && preg_match('/[a-zA-Z]/', $rawTitle)) {
            Log::warning('PredictionService: Persian title contained Latin characters. Retrying generation.', [
                'title' => $rawTitle,
            ]);
            throw new Exception('Generated text contains language/script leaks. Requesting a retry.');
        }

        // 2. Duplicate Detection via SHA-256 Hash
        $normalizedTitle = mb_strtolower($rawTitle);
        $titleHash = hash('sha256', $normalizedTitle);

        if (Prediction::where('title_hash', $titleHash)->exists()) {
            Log::info('PredictionService: Duplicate prediction title hash matched. Skipping.', [
                'title' => $rawTitle,
            ]);
            throw new Exception('Duplicate prediction detected. Retrying for a new topic.');
        }

        // 3. Save Entry in Database Transaction
        return DB::transaction(function () use ($structured, $params, $language, $region, $titleHash, $userId) {

            // Resolve Category
            $categoryId = null;
            $categoryName = $structured['category'] ?? $params['category'] ?? null;
            if ($categoryName) {
                $category = Category::firstOrCreate(
                    ['title' => $categoryName],
                    ['status' => CategoryStatus::ACTIVE]
                );
                $categoryId = $category->id;
            }

            // Resolve Topic
            $topicId = null;
            $topicName = $structured['topic'] ?? $params['topic'] ?? null;
            if ($topicName) {
                $topic = Topic::firstOrCreate(
                    ['title' => $topicName],
                    ['status' => TopicStatus::ACTIVE, 'icon' => 'no-icon']
                );
                $topicId = $topic->id;
            }

            // Create Prediction Record
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

            // Create Options
            if (!empty($structured['options']) && is_array($structured['options'])) {
                foreach ($structured['options'] as $optionText) {
                    $prediction->predictionOptions()->create([
                        'title'   => $optionText,
                        'is_true' => false,
                    ]);
                }
            }

            // Sync Tags
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
}
