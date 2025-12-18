<?php

use App\Models\Comment;
use App\Models\User;
use App\Repositories\Comment\CommentRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(CommentRepositoryInterface::class);
    $this->app->instance(CommentRepositoryInterface::class, $this->repository);
});

test('it returns comment resource when found', function () {
    $comment = Comment::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($comment);
    
    $response = $this->getJson('/api/v1/comments/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it creates comment with valid data', function () {
    $user = User::factory()->make(['id' => 1]);
    $comment = Comment::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn($comment);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/comments', [
            'question_id' => 1,
            'text' => 'Test comment',
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it validates required fields', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/comments', []);
    
    $response->assertStatus(422);
});

test('it updates comment', function () {
    $user = User::factory()->make(['id' => 1]);
    $comment = Comment::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findByIdLight')
        ->with(1)
        ->once()
        ->andReturn($comment);
    
    $this->repository->shouldReceive('update')
        ->with(1, ['text' => 'Updated'])
        ->once()
        ->andReturn(true);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($comment);
    
    $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/comments/1', [
            'text' => 'Updated',
        ]);
    
    $response->assertStatus(200);
});

test('it deletes comment', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('delete')
        ->with(1)
        ->once()
        ->andReturn(true);
    
    $response = $this->actingAs($user, 'sanctum')
        ->deleteJson('/api/v1/comments/1');
    
    $response->assertStatus(200);
});

