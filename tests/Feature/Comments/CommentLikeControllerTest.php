<?php

use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    $this->repository = Mockery::mock(CommentRepositoryInterface::class);
    $this->app->instance(CommentRepositoryInterface::class, $this->repository);
    Bus::fake();
});

test('it toggles comment like successfully', function () {
    $user = \App\Models\User::factory()->make(['id' => 1]);
    $comment = Comment::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findByIdLight')
        ->with(1)
        ->once()
        ->andReturn($comment);
    
    $this->repository->shouldReceive('toggleCommentLike')
        ->with(1, 1)
        ->once()
        ->andReturn(true);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/comment-likes/1/toggle');
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => ['is_liked' => true],
        ]);
});

test('it returns 404 when comment not found', function () {
    $user = \App\Models\User::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findByIdLight')
        ->with(999)
        ->once()
        ->andReturn(null);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/comment-likes/999/toggle');
    
    $response->assertStatus(404);
});

test('it requires authentication', function () {
    $response = $this->postJson('/api/v1/comment-likes/1/toggle');
    
    $response->assertStatus(401);
});

test('it toggles from like to unlike', function () {
    $user = \App\Models\User::factory()->make(['id' => 1]);
    $comment = Comment::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findByIdLight')
        ->with(1)
        ->once()
        ->andReturn($comment);
    
    $this->repository->shouldReceive('toggleCommentLike')
        ->with(1, 1)
        ->once()
        ->andReturn(false);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/comment-likes/1/toggle');
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => ['is_liked' => false],
        ]);
});

