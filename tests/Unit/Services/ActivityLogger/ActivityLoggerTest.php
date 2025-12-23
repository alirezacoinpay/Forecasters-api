<?php

use App\Enums\ActivityAction;
use App\Jobs\LogActivityJob;
use App\Models\Prediction;
use App\Services\ActivityLogger\ActivityLogger;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Bus::fake();
});

test('it logs activity when user id is provided', function () {
    $userId = 1;
    $action = ActivityAction::PREDICTION_CREATE;
    $subject = Mockery::mock(Prediction::class);
    $subject->shouldReceive('getKey')->andReturn(1);
    $subject->shouldReceive('getMorphClass')->andReturn('App\Models\Prediction');

    ActivityLogger::log($userId, $action, $subject);

    Bus::assertDispatched(LogActivityJob::class, function ($job) use ($userId, $action) {
        $reflection = new \ReflectionClass($job);
        $property = $reflection->getProperty('payload');
        $property->setAccessible(true);
        $payload = $property->getValue($job);
        return $payload['user_id'] === $userId
            && $payload['action'] === $action;
    });
});

test('it does not log activity when user id is null', function () {
    $action = ActivityAction::PREDICTION_CREATE;
    $subject = Mockery::mock(Prediction::class);

    ActivityLogger::log(null, $action, $subject);

    Bus::assertNothingDispatched();
});

test('it includes metadata in activity log', function () {
    $userId = 1;
    $action = ActivityAction::PREDICTION_CREATE;
    $subject = Mockery::mock(Prediction::class);
    $subject->shouldReceive('getKey')->andReturn(1);
    $subject->shouldReceive('getMorphClass')->andReturn('App\Models\Prediction');
    $metadata = ['key' => 'value'];

    ActivityLogger::log($userId, $action, $subject, $metadata);

    Bus::assertDispatched(LogActivityJob::class, function ($job) use ($metadata) {
        $reflection = new \ReflectionClass($job);
        $property = $reflection->getProperty('payload');
        $property->setAccessible(true);
        $payload = $property->getValue($job);
        return $payload['metadata'] === $metadata;
    });
});

