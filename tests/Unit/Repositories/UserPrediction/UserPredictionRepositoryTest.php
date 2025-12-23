<?php

use App\Models\UserPrediction;
use App\Models\PredictionLike;
use App\Repositories\UserPrediction\UserPredictionRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(UserPrediction::class);
    $this->repository = new UserPredictionRepository($this->model);
});

test('findById includes likes when authenticated', function () {
    $prediction = Mockery::mock(UserPrediction::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with('predictionLikes')->once()->andReturn($query);
    $query->shouldReceive('with')->with(Mockery::on(function ($callback) {
        return is_callable($callback);
    }))->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    auth()->shouldReceive('check')->andReturn(true);
    auth()->shouldReceive('id')->andReturn(1);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($prediction);
});

test('togglePredictionLike creates like when not exists', function () {
    $prediction = Mockery::mock(\App\Models\Prediction::class);
    $query = Mockery::mock(Builder::class);
    
    \App\Models\Prediction::shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    PredictionLike::shouldReceive('where')->with('prediction_id', 1)->once()->andReturn($query);
    $query->shouldReceive('where')->with('user_id', 1)->once()->andReturn($query);
    $query->shouldReceive('first')->once()->andReturn(null);
    
    PredictionLike::shouldReceive('create')->with([
        'prediction_id' => 1,
        'user_id' => 1,
    ])->once()->andReturn(true);
    
    $result = $this->repository->togglePredictionLike(1, 1);
    
    expect($result)->toBeTrue();
});

test('togglePredictionLike deletes like when exists', function () {
    $prediction = Mockery::mock(\App\Models\Prediction::class);
    $existingLike = Mockery::mock(PredictionLike::class);
    $query = Mockery::mock(Builder::class);
    
    \App\Models\Prediction::shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    PredictionLike::shouldReceive('where')->with('prediction_id', 1)->once()->andReturn($query);
    $query->shouldReceive('where')->with('user_id', 1)->once()->andReturn($query);
    $query->shouldReceive('first')->once()->andReturn($existingLike);
    
    $existingLike->shouldReceive('delete')->once()->andReturn(true);
    
    $result = $this->repository->togglePredictionLike(1, 1);
    
    expect($result)->toBeFalse();
});

test('findByIdWithLikes includes user like when userId provided', function () {
    $prediction = Mockery::mock(UserPrediction::class);
    $query = Mockery::mock(Builder::class);
    $relationQuery = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with('predictionLikes')->once()->andReturn($query);
    $query->shouldReceive('with')->with(Mockery::on(function ($arg) {
        return is_array($arg) && isset($arg['myPredictionLike']) && is_callable($arg['myPredictionLike']);
    }))->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    $result = $this->repository->findByIdWithLikes(1, 1);
    
    expect($result)->toBe($prediction);
});

