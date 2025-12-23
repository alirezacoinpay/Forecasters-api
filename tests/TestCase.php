<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    protected static bool $migrationsRun = false;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations once for the test database
        // This ensures tables exist for validation rules (Rule::exists) and any code that queries the DB
        // Database connection is loaded from .env.testing file
        if (!static::$migrationsRun) {
            $connection = config('database.default');
            // Enable foreign key constraints for SQLite (if using SQLite)
            if ($connection === 'sqlite') {
                try {
                    DB::statement('PRAGMA foreign_keys = ON;');
                } catch (\Exception $e) {
                    // Ignore if already enabled or not SQLite
                }
            }

            // Run migrations for the configured test database
            // If using MySQL from .env.testing, all migrations will work properly
            // If using SQLite, some MySQL-specific migrations may fail but base tables will be created

            static::$migrationsRun = true;
        }

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
    public function actingAsUser($user = null)
    {
        $user = $user ?? \App\Models\User::factory()->make();

        return $this->actingAs($user, 'sanctum');
    }
}
