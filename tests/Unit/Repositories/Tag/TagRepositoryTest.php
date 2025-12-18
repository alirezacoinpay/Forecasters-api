<?php

use App\Models\Tag;
use App\Repositories\Tag\TagRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(Tag::class);
    $this->repository = new TagRepository($this->model);
});

test('findById returns tag with trashed', function () {
    $tag = Mockery::mock(Tag::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($tag);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($tag);
});

test('all returns collection', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(Tag::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->all([]);
    
    expect($result)->toBe($collection);
});

