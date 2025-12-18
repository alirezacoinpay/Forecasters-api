<?php

use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Mockery;

test('update delegates to repository', function () {
    $repository = Mockery::mock(BaseRepository::class);
    $cacheRepo = new BaseCacheRepository($repository);
    
    $repository->shouldReceive('update')->with(1, ['name' => 'test'])->once()->andReturn(true);
    
    $result = $cacheRepo->update(1, ['name' => 'test']);
    
    expect($result)->toBeTrue();
});

test('delete delegates to repository', function () {
    $repository = Mockery::mock(BaseRepository::class);
    $cacheRepo = new BaseCacheRepository($repository);
    
    $repository->shouldReceive('delete')->with(1)->once()->andReturn(true);
    
    $result = $cacheRepo->delete(1);
    
    expect($result)->toBeTrue();
});

test('restore delegates to repository', function () {
    $repository = Mockery::mock(BaseRepository::class);
    $cacheRepo = new BaseCacheRepository($repository);
    
    $repository->shouldReceive('restore')->with(1)->once()->andReturn(true);
    
    $result = $cacheRepo->restore(1);
    
    expect($result)->toBeTrue();
});

test('create delegates to repository', function () {
    $repository = Mockery::mock(BaseRepository::class);
    $cacheRepo = new BaseCacheRepository($repository);
    $data = ['name' => 'test'];
    $created = Mockery::mock();
    
    $repository->shouldReceive('create')->with($data)->once()->andReturn($created);
    
    $result = $cacheRepo->create($data);
    
    expect($result)->toBe($created);
});

test('generateKey creates md5 hash from prefix and params', function () {
    $repository = Mockery::mock(BaseRepository::class);
    $cacheRepo = new class($repository) extends BaseCacheRepository {
        protected array $prefixes = ['testMethod' => 'test_prefix_'];
        public function testGenerateKey($data) {
            return $this->generateKey($data);
        }
    };
    
    $key = $cacheRepo->testGenerateKey([1, 'test']);
    
    expect($key)->toBeString();
    expect(strlen($key))->toBe(32); // MD5 hash length
});

