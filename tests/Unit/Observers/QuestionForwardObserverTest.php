<?php

use App\Enums\ActivityAction;
use App\Models\Question;
use App\Models\QuestionForward;
use App\Observers\QuestionForwardObserver;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
    $this->observer = new QuestionForwardObserver();
});

test('created logs share activity', function () {
    $question = Question::factory()->make(['id' => 1]);
    $forward = QuestionForward::factory()->make(['id' => 1, 'user_id' => 1]);
    $forward->setRelation('question', $question);
    
    $this->observer->created($forward);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::SHARE;
    });
});

