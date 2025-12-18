<?php

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery;

test('findByIdLight returns model when found', function () {
    $model = Mockery::mock(Model::class);
    $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
    $result = Mockery::mock(Model::class);
    
    $model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($result);
    
    $repository = new BaseRepository($model);
    $found = $repository->findByIdLight(1);
    
    expect($found)->toBe($result);
});

test('findByIdLight includes trashed when model uses SoftDeletes', function () {
    $model = Mockery::mock(Model::class, SoftDeletes::class);
    $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
    $result = Mockery::mock(Model::class);
    
    $model->shouldReceive('newQuery')->once()->andReturn($query);
    $query->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($result);
    
    $repository = new BaseRepository($model);
    $found = $repository->findByIdLight(1);
    
    expect($found)->toBe($result);
});

test('create calls model create method', function () {
    $model = Mockery::mock(Model::class);
    $data = ['name' => 'Test'];
    $created = Mockery::mock(Model::class);
    
    $model->shouldReceive('create')->with($data)->once()->andReturn($created);
    
    $repository = new BaseRepository($model);
    $result = $repository->create($data);
    
    expect($result)->toBe($created);
});

test('update calls model update method', function () {
    $model = Mockery::mock(Model::class);
    $found = Mockery::mock(Model::class);
    $data = ['name' => 'Updated'];
    
    $model->shouldReceive('find')->with(1)->once()->andReturn($found);
    $found->shouldReceive('update')->with($data)->once()->andReturn(true);
    
    $repository = new BaseRepository($model);
    $result = $repository->update(1, $data);
    
    expect($result)->toBeTrue();
});

test('update returns null when model not found', function () {
    $model = Mockery::mock(Model::class);
    
    $model->shouldReceive('find')->with(999)->once()->andReturn(null);
    
    $repository = new BaseRepository($model);
    $result = $repository->update(999, []);
    
    expect($result)->toBeNull();
});

test('delete calls model delete method', function () {
    $model = Mockery::mock(Model::class);
    $found = Mockery::mock(Model::class);
    
    $model->shouldReceive('find')->with(1)->once()->andReturn($found);
    $found->shouldReceive('delete')->once()->andReturn(true);
    
    $repository = new BaseRepository($model);
    $result = $repository->delete(1);
    
    expect($result)->toBeTrue();
});

test('restore calls model restore method', function () {
    $model = Mockery::mock(Model::class);
    $query = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
    $found = Mockery::mock(Model::class);
    
    $model->shouldReceive('onlyTrashed')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($found);
    $found->shouldReceive('restore')->once()->andReturn(true);
    
    $repository = new BaseRepository($model);
    $result = $repository->restore(1);
    
    expect($result)->toBeTrue();
});

