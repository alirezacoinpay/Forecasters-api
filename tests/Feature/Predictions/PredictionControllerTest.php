<?php

use App\Models\Prediction;
use App\Models\User;
use App\Repositories\Prediction\PredictionRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(PredictionRepositoryInterface::class);
    $this->app->instance(PredictionRepositoryInterface::class, $this->repository);
});

test('it returns prediction resource when found', function () {
    $user = User::factory()->make(['id' => 1]);
    $prediction = Prediction::factory()->make(['id' => 1]);

    $this->repository->shouldReceive('userFeedPrediction')
        ->with(1, 1)
        ->once()
        ->andReturn($prediction);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/predictions/1');

    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns 404 when prediction not found', function () {
    $user = User::factory()->make(['id' => 1]);

    $this->repository->shouldReceive('userFeedPrediction')
        ->with(999, 1)
        ->once()
        ->andReturn(null);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/predictions/999');

    $response->assertStatus(404);
});

test('it requires authentication', function () {
    $response = $this->getJson('/api/v1/predictions/1');

    $response->assertStatus(401);
});

