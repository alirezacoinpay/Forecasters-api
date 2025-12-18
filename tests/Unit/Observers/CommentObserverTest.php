<?php

use App\Enums\ActivityAction;
use App\Models\Comment;
use App\Models\Question;
use App\Observers\CommentObserver;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Bus::fake();
    $this->observer = new CommentObserver();
});

test('created logs comment reply when parent_id exists', function () {
    $comment = Comment::factory()->make(['id' => 1, 'user_id' => 1, 'parent_id' => 2]);
    
    $this->observer->created($comment);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::COMMENT_REPLY;
    });
});

test('created logs comment activity', function () {
    $question = Question::factory()->make(['id' => 1]);
    $comment = Comment::factory()->make(['id' => 1, 'user_id' => 1, 'parent_id' => null]);
    $comment->setRelation('question', $question);
    
    $this->observer->created($comment);
    
    Bus::assertDispatched(\App\Jobs\LogActivityJob::class, function ($job) {
        return $job->payload['action'] === ActivityAction::COMMENT;
    });
});

