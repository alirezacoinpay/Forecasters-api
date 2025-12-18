<?php

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(UserRepositoryInterface::class);
    $this->app->instance(UserRepositoryInterface::class, $this->repository);
});

test('it returns authenticated user', function () {
    $user = User::factory()->make(['id' => 1]);
    $user->setRelation('userPredictions', collect([]));
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/me');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it updates user profile', function () {
    $user = User::factory()->make(['id' => 1]);
    $updatedUser = User::factory()->make(['id' => 1, 'username' => 'Updated']);
    
    $this->repository->shouldReceive('update')
        ->with(1, ['username' => 'Updated'])
        ->once()
        ->andReturn(true);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($updatedUser);
    
    $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/edit-profile', [
            'username' => 'Updated',
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

