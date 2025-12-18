<?php

use App\Models\User;
use App\Models\UserPrediction;
use App\Repositories\UserPrediction\UserPredictionRepositoryInterface;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    $this->repository = Mockery::mock(UserPredictionRepositoryInterface::class);
    $this->app->instance(UserPredictionRepositoryInterface::class, $this->repository);
    Bus::fake();
});

test('it toggles prediction like successfully', function () {
    $user = User::factory()->make(['id' => 1]);
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findByIdLight')
        ->with(1)
        ->once()
        ->andReturn($prediction);
    
    $this->repository->shouldReceive('togglePredictionLike')
        ->with(1, 1)
        ->once()
        ->andReturn(true);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/prediction-likes/1/toggle');
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => ['is_liked' => true],
        ]);
});

test('it returns 404 when prediction not found', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findByIdLight')
        ->with(999)
        ->once()
        ->andReturn(null);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/prediction-likes/999/toggle');
    
    $response->assertStatus(404);
});

