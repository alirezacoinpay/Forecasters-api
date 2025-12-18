<?php

namespace Database\Factories;

use App\Models\QuestionForward;
use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionForward>
 */
class QuestionForwardFactory extends Factory
{
    protected $model = QuestionForward::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'user_id' => User::factory(),
            'target' => fake()->randomElement(['telegram', 'whatsapp', 'email']),
        ];
    }
}

