<?php

use App\Models\Question;
use App\Models\User;
use App\Repositories\Question\QuestionRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(QuestionRepositoryInterface::class);
    $this->app->instance(QuestionRepositoryInterface::class, $this->repository);
});

test('it returns search results', function () {
    $user = User::factory()->make(['id' => 1]);
    $questions = collect([Question::factory()->make()]);
    
    $this->repository->shouldReceive('userSearchQuestions')
        ->with(1, ['search' => 'test'])
        ->once()
        ->andReturn($questions);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/search?search=test');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns search history', function () {
    $user = User::factory()->make(['id' => 1]);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/search-history');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

