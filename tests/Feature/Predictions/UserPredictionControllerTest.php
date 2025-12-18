<?php

use App\Models\User;
use App\Models\UserPrediction;
use App\Models\QuestionOption;
use App\Repositories\UserPrediction\UserPredictionRepositoryInterface;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(UserPredictionRepositoryInterface::class);
    $this->questionRepository = Mockery::mock(QuestionRepositoryInterface::class);
    $this->commentRepository = Mockery::mock(CommentRepositoryInterface::class);
    
    $this->app->instance(UserPredictionRepositoryInterface::class, $this->repository);
    $this->app->instance(QuestionRepositoryInterface::class, $this->questionRepository);
    $this->app->instance(CommentRepositoryInterface::class, $this->commentRepository);
});

test('it returns prediction resource when found', function () {
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($prediction);
    
    $response = $this->getJson('/api/v1/predictions/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it creates prediction with valid data', function () {
    $user = User::factory()->make(['id' => 1]);
    $option = QuestionOption::factory()->make(['id' => 1, 'question_id' => 1]);
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $this->questionRepository->shouldReceive('findQuestionOptionByIdLight')
        ->with(1)
        ->once()
        ->andReturn($option);
    
    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn($prediction);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/predictions', [
            'question_option_id' => 1,
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns 404 when question option not found', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $this->questionRepository->shouldReceive('findQuestionOptionByIdLight')
        ->with(999)
        ->once()
        ->andReturn(null);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/predictions', [
            'question_option_id' => 999,
        ]);
    
    $response->assertStatus(404);
});

