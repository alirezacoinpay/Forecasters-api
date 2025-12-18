<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'prediction_id' => Prediction::factory(),
            'parent_id' => null,
            'text' => fake()->paragraph(),
            'file' => null,
        ];
    }

    public function reply(Comment $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'prediction_id' => $parent->prediction_id,
        ]);
    }
}

