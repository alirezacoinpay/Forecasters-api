<?php

namespace Database\Factories;

use App\Models\PredictionForward;
use App\Models\User;
use App\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PredictionForward>
 */
class PredictionForwardFactory extends Factory
{
    protected $model = PredictionForward::class;

    public function definition(): array
    {
        return [
            'prediction_id' => Prediction::factory(),
            'user_id' => User::factory(),
            'target' => fake()->randomElement(['telegram', 'whatsapp', 'email']),
        ];
    }
}

