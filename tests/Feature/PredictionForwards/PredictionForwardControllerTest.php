<?php

use App\Models\User;
use App\Models\PredictionForward;
use App\Repositories\PredictionForward\PredictionForwardRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(PredictionForwardRepositoryInterface::class);
    $this->app->instance(PredictionForwardRepositoryInterface::class, $this->repository);
});

test('it creates prediction forward', function () {
    $user = User::factory()->make(['id' => 1]);
    $forward = PredictionForward::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn($forward);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/prediction-forwards', [
            'prediction_id' => 1,
            'target' => 'telegram',
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

