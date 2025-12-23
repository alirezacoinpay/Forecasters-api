<?php

use App\Http\Requests\Client\Comments\UpdateCommentRequest;

test('it validates prediction_id exists when provided', function () {
    $request = new UpdateCommentRequest();
    
    $validator = validator([
        'prediction_id' => 99999,
    ], $request->rules());
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid update data', function () {
    $request = new UpdateCommentRequest();
    
    $validator = validator([
        'text' => 'Updated comment',
    ], $request->rules());
    
    expect($validator->fails())->toBeFalse();
});

