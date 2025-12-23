<?php

use App\Jobs\LogActivityJob;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('handle creates activity log', function () {
    $payload = [
        'user_id' => 1,
        'action' => \App\Enums\ActivityAction::PREDICTION_CREATE,
        'subject_id' => 1,
        'subject_type' => 'App\Models\Prediction',
        'metadata' => [],
    ];
    
    $job = new LogActivityJob($payload);
    $query = Mockery::mock(Builder::class);
    
    // Use alias to mock static method
    $activityLogMock = Mockery::mock('alias:App\Models\ActivityLog');
    $activityLogMock->shouldReceive('query')->once()->andReturn($query);
    $query->shouldReceive('create')->with($payload)->once()->andReturn(true);
    
    $job->handle();
    
    // If we get here without exception, test passes
    expect(true)->toBeTrue();
});

