<?php

use App\Models\Question;
use App\Models\User;
use App\Repositories\Question\QuestionRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(QuestionRepositoryInterface::class);
    $this->app->instance(QuestionRepositoryInterface::class, $this->repository);
});

test('it returns question resource when found', function () {
    $user = User::factory()->make(['id' => 1]);
    $question = Question::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('userFeedQuestion')
        ->with(1, 1)
        ->once()
        ->andReturn($question);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/questions/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns 404 when question not found', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('userFeedQuestion')
        ->with(999, 1)
        ->once()
        ->andReturn(null);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/questions/999');
    
    $response->assertStatus(404);
});

test('it works without authentication', function () {
    $question = Question::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('userFeedQuestion')
        ->with(1, null)
        ->once()
        ->andReturn($question);
    
    $response = $this->getJson('/api/v1/questions/1');
    
    $response->assertStatus(200);
});

