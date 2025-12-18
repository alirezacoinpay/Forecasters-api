<?php

use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(User::class);
    $this->repository = new UserRepository($this->model);
});

test('findById returns user', function () {
    $user = Mockery::mock(User::class);
    
    $this->model->shouldReceive('find')->with(1)->once()->andReturn($user);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($user);
});

test('findByMobileLight returns user by mobile', function () {
    $user = Mockery::mock(User::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('where')->with('mobile', '09123456789')->once()->andReturn($query);
    $query->shouldReceive('first')->once()->andReturn($user);
    
    $result = $this->repository->findByMobileLight('09123456789');
    
    expect($result)->toBe($user);
});

test('all returns collection', function () {
    $query = Mockery::mock(Builder::class);
    $collection = collect([Mockery::mock(User::class)]);
    
    $this->model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('orderBy')->with('id', 'desc')->once()->andReturn($query);
    $query->shouldReceive('get')->once()->andReturn($collection);
    
    $result = $this->repository->all([]);
    
    expect($result)->toBe($collection);
});

