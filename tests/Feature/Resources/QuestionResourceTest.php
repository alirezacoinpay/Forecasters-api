<?php

use App\Http\Resources\Client\QuestionResource;
use App\Models\Question;

test('it transforms question to array', function () {
    $question = Question::factory()->make([
        'id' => 1,
        'title' => 'Test Question',
        'text' => 'Question text',
    ]);
    
    $resource = new QuestionResource($question);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKeys(['id', 'title', 'text', 'time_past']);
    expect($array['title'])->toBe('Test Question');
});

test('it includes counts when loaded', function () {
    $question = Question::factory()->make(['id' => 1]);
    $question->setAttribute('user_predictions_count', 10);
    $question->setAttribute('comments_count', 5);
    
    $resource = new QuestionResource($question);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKey('userPredictionsCount');
    expect($array)->toHaveKey('commentsCount');
});

