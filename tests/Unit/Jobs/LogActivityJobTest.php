<?php

use App\Jobs\LogActivityJob;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

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
    
    ActivityLog::shouldReceive('query')->once()->andReturn($query);
    $query->shouldReceive('create')->with($payload)->once()->andReturn(true);
    
    $job->handle();
    
    // If we get here without exception, test passes
    expect(true)->toBeTrue();
});

