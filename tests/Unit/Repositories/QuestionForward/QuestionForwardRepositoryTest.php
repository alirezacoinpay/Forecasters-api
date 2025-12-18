<?php

use App\Models\QuestionForward;
use App\Repositories\QuestionForward\QuestionForwardRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(QuestionForward::class);
    $this->repository = new QuestionForwardRepository($this->model);
});

test('findById returns question forward with trashed', function () {
    $forward = Mockery::mock(QuestionForward::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($forward);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($forward);
});

test('all returns only trashed when trashed param is true', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(QuestionForward::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('onlyTrashed')->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->all(['trashed' => true]);
    
    expect($result)->toBe($collection);
});

