<?php

namespace Database\Factories;

use App\Models\PredictionLike;
use App\Models\User;
use App\Models\UserPrediction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PredictionLike>
 */
class PredictionLikeFactory extends Factory
{
    protected $model = PredictionLike::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'question_id' => UserPrediction::factory(),
        ];
    }
}

