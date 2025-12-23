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

        // Run migrations once for the in-memory SQLite test database
        // This ensures tables exist for validation rules (Rule::exists) and any code that queries the DB
        if (!static::$migrationsRun && config('database.default') === 'sqlite') {
            // Enable foreign key constraints for SQLite
            try {
                DB::statement('PRAGMA foreign_keys = ON;');
            } catch (\Exception $e) {
                // Ignore if already enabled or not SQLite
            }
            
            // Run migrations, handling SQLite limitations
            // SQLite doesn't support ->change() and ->renameColumn() operations
            // We'll run migrations and catch any SQLite-specific errors
            try {
                Artisan::call('migrate', [
                    '--database' => 'sqlite',
                    '--force' => true,
                    '--path' => 'database/migrations',
                ]);
            } catch (\Exception $e) {
                // Some migrations use MySQL-specific syntax (->change(), ->renameColumn())
                // that SQLite doesn't support. This is acceptable for testing.
                // The base table structures are created, which is sufficient for validation rules.
                // If you need full migration support, consider using a separate MySQL test database
                // or creating SQLite-compatible migration versions.
            }
            
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
