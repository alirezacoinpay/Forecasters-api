<?php

use App\Models\Tag;
use App\Repositories\Tag\TagRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(TagRepositoryInterface::class);
    $this->app->instance(TagRepositoryInterface::class, $this->repository);
});

test('it returns tag resource when found', function () {
    $tag = Tag::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($tag);
    
    $response = $this->getJson('/api/v1/tags/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns list of tags', function () {
    $tags = collect([Tag::factory()->make()]);
    
    $this->repository->shouldReceive('all')
        ->with([])
        ->once()
        ->andReturn($tags);
    
    $response = $this->getJson('/api/v1/tags');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

