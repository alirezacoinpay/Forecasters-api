<?php

use App\Models\Prediction;
use App\Models\User;
use App\Repositories\Prediction\PredictionRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(PredictionRepositoryInterface::class);
    $this->app->instance(PredictionRepositoryInterface::class, $this->repository);
});

test('it returns search results', function () {
    $user = User::factory()->make(['id' => 1]);
    $predictions = collect([Prediction::factory()->make()]);
    
    $this->repository->shouldReceive('userSearchPredictions')
        ->with(1, ['search' => 'test'])
        ->once()
        ->andReturn($predictions);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/search?search=test');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns search history', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/search-history');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

