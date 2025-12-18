<?php

use App\Enums\ActivityAction;
use App\Models\Question;
use App\Observers\QuestionObserver;
use App\Services\ActivityLogger\ActivityLogger;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
    $this->observer = new QuestionObserver();
});

test('created logs activity when user_id exists', function () {
    $question = Question::factory()->make(['id' => 1, 'user_id' => 1]);
    
    $this->observer->created($question);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::QUESTION_CREATE;
    });
});

test('created does not log when user_id is null', function () {
    $question = Question::factory()->make(['id' => 1, 'user_id' => null]);
    
    $this->observer->created($question);
    
    Bus::assertNothingDispatched();
});

test('updated logs activity when user_id exists', function () {
    $question = Question::factory()->make(['id' => 1, 'user_id' => 1]);
    
    $this->observer->updated($question);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::QUESTION_EDIT;
    });
});

test('deleted logs activity when user_id exists', function () {
    $question = Question::factory()->make(['id' => 1, 'user_id' => 1]);
    
    $this->observer->deleted($question);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::QUESTION_DELETE;
    });
});

