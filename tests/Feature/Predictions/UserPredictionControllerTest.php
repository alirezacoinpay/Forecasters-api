<?php

use App\Models\User;
use App\Models\UserPrediction;
use App\Models\PredictionOption;
use App\Repositories\UserPrediction\UserPredictionRepositoryInterface;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(UserPredictionRepositoryInterface::class);
    $this->predictionRepository = Mockery::mock(PredictionRepositoryInterface::class);
    $this->commentRepository = Mockery::mock(CommentRepositoryInterface::class);
    
    $this->app->instance(UserPredictionRepositoryInterface::class, $this->repository);
    $this->app->instance(PredictionRepositoryInterface::class, $this->predictionRepository);
    $this->app->instance(CommentRepositoryInterface::class, $this->commentRepository);
});

test('it returns prediction resource when found', function () {
    $user = User::factory()->make(['id' => 1]);
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($prediction);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/predictions/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it creates prediction with valid data', function () {
    $user = User::factory()->make(['id' => 1]);
    $option = PredictionOption::factory()->make(['id' => 1, 'prediction_id' => 1]);
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $this->predictionRepository->shouldReceive('findPredictionOptionByIdLight')
        ->with(1)
        ->once()
        ->andReturn($option);
    
    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn($prediction);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/predictions', [
            'prediction_option_id' => 1,
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns 404 when prediction option not found', function () {
    $user = User::factory()->make(['id' => 1]);
    
    // Create a prediction option in the database so validation passes
    // The validation Rule::exists will check the database
    $option = \App\Models\PredictionOption::factory()->create(['id' => 999]);
    
    // Mock the repository to return null (simulating not found after validation passes)
    $this->predictionRepository->shouldReceive('findPredictionOptionByIdLight')
        ->with(999)
        ->once()
        ->andReturn(null);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/predictions', [
            'prediction_option_id' => 999,
        ]);
    
    // Clean up
    $option->delete();
    
    $response->assertStatus(404);
});

