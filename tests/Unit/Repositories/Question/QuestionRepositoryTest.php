<?php

use App\Models\Question;
use App\Models\QuestionOption;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(Question::class);
    $this->questionOptionModel = Mockery::mock(QuestionOption::class);
    $this->repository = new QuestionRepository($this->model, $this->questionOptionModel);
});

test('findFeedPage returns question with relationships', function () {
    $question = Mockery::mock(Question::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with(['userPredictions', 'comments'])->once()->andReturn($query);
    $query->shouldReceive('with')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($question);
    
    auth()->shouldReceive('id')->andReturn(1);
    
    $result = $this->repository->findFeedPage(1);
    
    expect($result)->toBe($question);
});

test('userFeedQuestion includes user prediction', function () {
    $question = Mockery::mock(Question::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('with')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('withCount')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($question);
    
    $result = $this->repository->userFeedQuestion(1, 1);
    
    expect($result)->toBe($question);
});

test('findQuestionOptionByIdLight returns option', function () {
    $option = Mockery::mock(QuestionOption::class);
    $query = Mockery::mock(Builder::class);
    
    $this->questionOptionModel->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($option);
    
    $result = $this->repository->findQuestionOptionByIdLight(1);
    
    expect($result)->toBe($option);
});

test('userFeedQuestions returns questions with filters', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(Question::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('with')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('withCount')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('where')->with('topic_id', 1)->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->userFeedQuestions(1, ['topic_id' => 1]);
    
    expect($result)->toBe($collection);
});

