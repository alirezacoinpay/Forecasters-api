<?php

use App\Enums\ActivityAction;
use App\Models\Prediction;
use App\Models\UserPrediction;
use App\Observers\UserPredictionObserver;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
    $this->observer = new UserPredictionObserver();
});

test('created logs predict activity', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    $userPrediction = UserPrediction::factory()->make(['id' => 1, 'user_id' => 1]);
    $userPrediction->setRelation('prediction', $prediction);
    
    $this->observer->created($userPrediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICT;
    });
});

test('updated logs prediction change activity', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    $userPrediction = UserPrediction::factory()->make(['id' => 1, 'user_id' => 1]);
    $userPrediction->setRelation('prediction', $prediction);
    
    $this->observer->updated($userPrediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICTION_CHANGE;
    });
});

