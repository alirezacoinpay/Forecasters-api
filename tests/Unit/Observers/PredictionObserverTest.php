<?php

use App\Enums\ActivityAction;
use App\Models\Prediction;
use App\Observers\PredictionObserver;
use App\Services\ActivityLogger\ActivityLogger;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
    $this->observer = new PredictionObserver();
});

test('created logs activity when user_id exists', function () {
    $prediction = Prediction::factory()->make(['id' => 1, 'user_id' => 1]);
    
    $this->observer->created($prediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICTION_CREATE;
    });
});

test('created does not log when user_id is null', function () {
    $prediction = Prediction::factory()->make(['id' => 1, 'user_id' => null]);
    
    $this->observer->created($prediction);
    
    Bus::assertNothingDispatched();
});

test('updated logs activity when user_id exists', function () {
    $prediction = Prediction::factory()->make(['id' => 1, 'user_id' => 1]);
    
    $this->observer->updated($prediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICTION_EDIT;
    });
});

test('deleted logs activity when user_id exists', function () {
    $prediction = Prediction::factory()->make(['id' => 1, 'user_id' => 1]);
    
    $this->observer->deleted($prediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICTION_DELETE;
    });
});

