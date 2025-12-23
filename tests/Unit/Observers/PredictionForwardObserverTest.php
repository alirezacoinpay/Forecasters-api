<?php

use App\Enums\ActivityAction;
use App\Models\Prediction;
use App\Models\PredictionForward;
use App\Observers\PredictionForwardObserver;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Bus::fake();
    $this->observer = new PredictionForwardObserver();
});

test('created logs share activity', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    $forward = PredictionForward::factory()->make(['id' => 1, 'user_id' => 1]);
    $forward->setRelation('prediction', $prediction);
    
    $this->observer->created($forward);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        $reflection = new \ReflectionClass($job);
        $property = $reflection->getProperty('payload');
        $property->setAccessible(true);
        $payload = $property->getValue($job);
        return $payload['action'] === ActivityAction::SHARE;
    });
});

