<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use App\Enums\ActivityAction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(ActivityAction::cases()),
            'subject_id' => 1,
            'subject_type' => 'App\Models\Question',
            'metadata' => [],
            'session_id' => fake()->uuid(),
            'device_type' => fake()->randomElement(['mobile', 'desktop', 'tablet']),
            'platform' => fake()->randomElement(['ios', 'android', 'web']),
            'created_at' => now(),
        ];
    }
}

