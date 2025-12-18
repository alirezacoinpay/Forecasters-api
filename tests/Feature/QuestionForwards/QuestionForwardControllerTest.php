<?php

use App\Models\User;
use App\Models\QuestionForward;
use App\Repositories\QuestionForward\QuestionForwardRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(QuestionForwardRepositoryInterface::class);
    $this->app->instance(QuestionForwardRepositoryInterface::class, $this->repository);
});

test('it creates question forward', function () {
    $user = User::factory()->make(['id' => 1]);
    $forward = QuestionForward::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('create')
        ->once()
        ->andReturn($forward);
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/question-forwards', [
            'question_id' => 1,
            'target' => 'telegram',
        ]);
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

