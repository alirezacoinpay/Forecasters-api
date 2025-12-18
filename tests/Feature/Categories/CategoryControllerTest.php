<?php

use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
    $this->app->instance(CategoryRepositoryInterface::class, $this->repository);
});

test('it returns category resource when found', function () {
    $category = Category::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($category);
    
    $response = $this->getJson('/api/v1/categories/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns list of categories', function () {
    $categories = collect([Category::factory()->make()]);
    
    $this->repository->shouldReceive('allFeedPage')
        ->with([])
        ->once()
        ->andReturn($categories);
    
    $response = $this->getJson('/api/v1/categories');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

