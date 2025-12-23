<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Mockery;

class TestHelpers
{
    /**
     * Assert that a job was dispatched
     */
    public static function assertJobDispatched(string $jobClass, ?callable $callback = null): void
    {
        Bus::assertDispatched($jobClass, $callback);
    }

    /**
     * Assert that a job was not dispatched
     */
    public static function assertJobNotDispatched(string $jobClass): void
    {
        Bus::assertNotDispatched($jobClass);
    }

    /**
     * Create a mock instance
     */
    public static function mock(string $class): Mockery\MockInterface
    {
        return Mockery::mock($class);
    }

    /**
     * Create a partial mock instance
     */
    public static function partialMock(string $class): Mockery\MockInterface
    {
        return Mockery::mock($class)->makePartial();
    }
}

