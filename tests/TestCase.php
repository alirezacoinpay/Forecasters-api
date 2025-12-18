<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake queues and buses for all tests
        Bus::fake();
        Queue::fake();
        
        // Clear cache before each test
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Create a mock repository instance
     */
    protected function mockRepository(string $interface): Mockery\MockInterface
    {
        return Mockery::mock($interface);
    }

    /**
     * Create an authenticated user for testing
     */
    protected function actingAsUser($user = null)
    {
        $user = $user ?? \App\Models\User::factory()->make();
        
        return $this->actingAs($user, 'sanctum');
    }
}
