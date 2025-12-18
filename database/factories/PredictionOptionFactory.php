<?php

namespace Database\Factories;

use App\Models\PredictionOption;
use App\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PredictionOption>
 */
class PredictionOptionFactory extends Factory
{
    protected $model = PredictionOption::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'prediction_id' => Prediction::factory(),
            'is_true' => false,
        ];
    }

    public function correct(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_true' => true,
        ]);
    }
}

