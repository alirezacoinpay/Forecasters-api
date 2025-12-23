<?php

use App\Models\Prediction;
use App\Models\User;
use App\Repositories\Prediction\PredictionRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(PredictionRepositoryInterface::class);
    $this->app->instance(PredictionRepositoryInterface::class, $this->repository);
});

test('it returns feed predictions', function () {
    $user = User::factory()->make(['id' => 1]);
    $predictions = collect([Prediction::factory()->make()]);
    
    $this->repository->shouldReceive('userFeedPredictions')
        ->with(1, [])
        ->once()
        ->andReturn($predictions);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/prediction-feed');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it filters feed by topic_id', function () {
    $user = User::factory()->make(['id' => 1]);
    $predictions = collect([Prediction::factory()->make()]);
    
    $this->repository->shouldReceive('userFeedPredictions')
        ->with(1, ['topic_id' => 1])
        ->once()
        ->andReturn($predictions);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/prediction-feed?topic_id=1');
    
    $response->assertStatus(200);
});

