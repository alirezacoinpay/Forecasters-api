<?php

use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(Category::class);
    $this->repository = new CategoryRepository($this->model);
});

test('findById returns category', function () {
    $category = Mockery::mock(Category::class);
    
    $this->model->shouldReceive('find')->with(1)->once()->andReturn($category);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($category);
});

test('all returns collection without pagination', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(Category::class)]);
    
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
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('paginate')->with(10)->once()->andReturn($paginator);
    
    $result = $this->repository->all(['paginate' => 10]);
    
    expect($result)->toBe($paginator);
});

test('allFeedPage returns active categories', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(Category::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('select')->with(['title', 'icon', 'id'])->once()->andReturn($query);
    $query->shouldReceive('where')->with('status', \App\Enums\CategoryStatus::ACTIVE)->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->allFeedPage([]);
    
    expect($result)->toBe($collection);
});

