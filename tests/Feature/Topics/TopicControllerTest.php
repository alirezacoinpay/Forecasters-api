<?php

use App\Models\Topic;
use App\Models\User;
use App\Repositories\Topic\TopicRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(TopicRepositoryInterface::class);
    $this->app->instance(TopicRepositoryInterface::class, $this->repository);
});

test('it returns topic resource when found', function () {
    $user = User::factory()->make(['id' => 1]);
    $topic = Topic::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($topic);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/topics/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns list of topics', function () {
    $user = User::factory()->make(['id' => 1]);
    $topics = collect([Topic::factory()->make()]);
    
    $this->repository->shouldReceive('all')
        ->with([])
        ->once()
        ->andReturn($topics);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/topics');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

