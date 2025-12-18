<?php

namespace Database\Factories;

use App\Models\QuestionOption;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionOption>
 */
class QuestionOptionFactory extends Factory
{
    protected $model = QuestionOption::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'question_id' => Question::factory(),
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

