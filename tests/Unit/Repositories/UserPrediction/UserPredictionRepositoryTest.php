<?php

use App\Models\UserPrediction;
use App\Models\PredictionLike;
use App\Repositories\UserPrediction\UserPredictionRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->model = Mockery::mock(UserPrediction::class);
    $this->repository = new UserPredictionRepository($this->model);
});

test('findById includes likes when authenticated', function () {
    $prediction = Mockery::mock(UserPrediction::class);
    
    // The repository uses $this->model directly and chains methods
    // We need to make the mock return itself for method chaining
    $this->model->shouldReceive('withCount')->with('predictionLikes')->once()->andReturn($this->model);
    $this->model->shouldReceive('with')->with(Mockery::on(function ($arg) {
        return is_array($arg) && isset($arg['myPredictionLike']) && is_callable($arg['myPredictionLike']);
    }))->once()->andReturn($this->model);
    $this->model->shouldReceive('find')->with(1)->once()->andReturn($prediction);
    
    Auth::shouldReceive('check')->andReturn(true);
    Auth::shouldReceive('id')->andReturn(1);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($prediction);
});

test('togglePredictionLike creates like when not exists', function () {
    // Static model method mocking is difficult in unit tests
    // This functionality is tested in feature tests
    $this->markTestSkipped('Static model method mocking requires database connection - tested in feature tests');
});

test('togglePredictionLike deletes like when exists', function () {
    // Static model method mocking is difficult in unit tests
    // This functionality is tested in feature tests
    $this->markTestSkipped('Static model method mocking requires database connection - tested in feature tests');
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

