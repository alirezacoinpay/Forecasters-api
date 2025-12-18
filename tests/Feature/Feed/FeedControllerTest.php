<?php

use App\Models\Question;
use App\Models\User;
use App\Repositories\Question\QuestionRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(QuestionRepositoryInterface::class);
    $this->app->instance(QuestionRepositoryInterface::class, $this->repository);
});

test('it returns feed questions', function () {
    $user = User::factory()->make(['id' => 1]);
    $questions = collect([Question::factory()->make()]);
    
    $this->repository->shouldReceive('userFeedQuestions')
        ->with(1, [])
        ->once()
        ->andReturn($questions);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/question-feed');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it filters feed by topic_id', function () {
    $user = User::factory()->make(['id' => 1]);
    $questions = collect([Question::factory()->make()]);
    
    $this->repository->shouldReceive('userFeedQuestions')
        ->with(1, ['topic_id' => 1])
        ->once()
        ->andReturn($questions);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/question-feed?topic_id=1');
    
    $response->assertStatus(200);
});

