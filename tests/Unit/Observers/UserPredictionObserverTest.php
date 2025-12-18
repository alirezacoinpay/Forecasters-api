<?php

use App\Enums\ActivityAction;
use App\Models\Question;
use App\Models\UserPrediction;
use App\Observers\UserPredictionObserver;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
    $this->observer = new UserPredictionObserver();
});

test('created logs predict activity', function () {
    $question = Question::factory()->make(['id' => 1]);
    $prediction = UserPrediction::factory()->make(['id' => 1, 'user_id' => 1]);
    $prediction->setRelation('question', $question);
    
    $this->observer->created($prediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICT;
    });
});

test('updated logs prediction change activity', function () {
    $question = Question::factory()->make(['id' => 1]);
    $prediction = UserPrediction::factory()->make(['id' => 1, 'user_id' => 1]);
    $prediction->setRelation('question', $question);
    
    $this->observer->updated($prediction);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::PREDICTION_CHANGE;
    });
});

