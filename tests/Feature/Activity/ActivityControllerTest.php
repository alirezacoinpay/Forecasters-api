<?php

use App\Enums\ActivityAction;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
});

test('it logs activity', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/activity', [
            'action' => ActivityAction::PREDICTION_VIEW->value,
            'subject_id' => 1,
            'subject_type' => 'App\Models\Prediction',
            'metadata' => [],
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class);
});

test('it requires authentication', function () {
    $response = $this->postJson('/api/v1/activity', [
        'action' => ActivityAction::PREDICTION_VIEW->value,
    ]);
    
    $response->assertStatus(401);
});

