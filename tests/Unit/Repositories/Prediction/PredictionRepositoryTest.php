<?php

use App\Models\Prediction;
use App\Models\PredictionOption;
use App\Repositories\Prediction\PredictionRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->model = Mockery::mock(Prediction::class);
    $this->predictionOptionModel = Mockery::mock(PredictionOption::class);
    $this->repository = new PredictionRepository($this->model, $this->predictionOptionModel);
});

test('findFeedPage returns prediction with relationships', function () {
    $prediction = Mockery::mock(Prediction::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with(['userPredictions', 'comments'])->once()->andReturn($query);
    $query->shouldReceive('with')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    Auth::shouldReceive('id')->andReturn(1);
    
    $result = $this->repository->findFeedPage(1);
    
    expect($result)->toBe($prediction);
});

test('userFeedPrediction includes user prediction', function () {
    $prediction = Mockery::mock(Prediction::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('with')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('withCount')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    $result = $this->repository->userFeedPrediction(1, 1);
    
    expect($result)->toBe($prediction);
});

test('findPredictionOptionByIdLight returns option', function () {
    $option = Mockery::mock(PredictionOption::class);
    $query = Mockery::mock(Builder::class);
    
    $this->predictionOptionModel->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($option);
    
    $result = $this->repository->findPredictionOptionByIdLight(1);
    
    expect($result)->toBe($option);
});

test('userFeedPredictions returns predictions with filters', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(Prediction::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('with')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('withCount')->atLeast()->once()->andReturn($query);
    $query->shouldReceive('where')->with('topic_id', 1)->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->userFeedPredictions(1, ['topic_id' => 1]);
    
    expect($result)->toBe($collection);
});

