<?php

namespace Database\Factories;

use App\Models\Prediction;
use App\Models\User;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prediction>
 */
class PredictionFactory extends Factory
{
    protected $model = Prediction::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'text' => fake()->paragraph(),
            'category_id' => Category::factory(),
            'topic_id' => Topic::factory(),
            'user_id' => User::factory(),
            'closes_at' => fake()->dateTimeBetween('now', '+1 month'),
            'starts_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'resolve_at' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}

