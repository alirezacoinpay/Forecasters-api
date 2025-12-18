<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(2, true),
            'icon' => fake()->word(),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}

