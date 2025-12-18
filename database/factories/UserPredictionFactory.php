<?php

namespace Database\Factories;

use App\Models\UserPrediction;
use App\Models\User;
use App\Models\QuestionOption;
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
            'question_option_id' => QuestionOption::factory(),
            'percentage' => fake()->numberBetween(0, 100),
        ];
    }
}

