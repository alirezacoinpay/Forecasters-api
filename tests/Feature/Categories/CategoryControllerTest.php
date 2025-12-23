<?php

use App\Models\Category;
use App\Models\User;
use App\Repositories\Category\CategoryRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
    $this->app->instance(CategoryRepositoryInterface::class, $this->repository);
});

test('it returns category resource when found', function () {
    $user = User::factory()->make(['id' => 1]);
    $category = Category::factory()->make(['id' => 1]);
    
    $this->repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($category);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/categories/1');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('it returns list of categories', function () {
    $user = User::factory()->make(['id' => 1]);
    $categories = collect([Category::factory()->make()]);
    
    $this->repository->shouldReceive('allFeedPage')
        ->with([])
        ->once()
        ->andReturn($categories);
    
    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/categories');
    
    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

