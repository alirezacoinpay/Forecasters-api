<?php

use App\Models\Topic;
use App\Repositories\Topic\TopicRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(Topic::class);
    $this->repository = new TopicRepository($this->model);
});

test('findById returns topic with trashed', function () {
    $topic = Mockery::mock(Topic::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($topic);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($topic);
});

test('all returns collection without pagination', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(Topic::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->all([]);
    
    expect($result)->toBe($collection);
});

test('all returns paginated results when paginate param provided', function () {
    $query = Mockery::mock(Builder::class);
    $paginator = Mockery::mock(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'asc')->once()->andReturn($query);
    $query->shouldReceive('paginate')->with(15)->once()->andReturn($paginator);
    
    $result = $this->repository->all(['paginate' => 15, 'sort' => 'asc']);
    
    expect($result)->toBe($paginator);
});

