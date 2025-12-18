<?php

namespace Database\Factories;

use App\Models\UserPrediction;
use App\Models\User;
use App\Models\PredictionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPrediction>
 */
class UserPredictionFactory extends Factory
{
    protected $model = UserPrediction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'prediction_option_id' => PredictionOption::factory(),
            'percentage' => fake()->numberBetween(0, 100),
        ];
    }
}

