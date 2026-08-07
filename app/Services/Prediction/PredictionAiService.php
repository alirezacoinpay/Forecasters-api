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
    public function generateAndSave(array $params = [], ?int $userId = null): Prediction
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
                    ['status' => TopicStatus::ACTIVE, 'icon' => 'no-icon']
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
}
