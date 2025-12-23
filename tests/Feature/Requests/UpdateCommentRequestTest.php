<?php

use App\Http\Requests\Client\Comments\UpdateCommentRequest;

test('it validates prediction_id exists when provided', function () {
    $request = new UpdateCommentRequest();
    
    $validator = validator($request->rules(), [
        'prediction_id' => 99999,
    ]);
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid update data', function () {
    $request = new UpdateCommentRequest();
    
    $validator = validator($request->rules(), [
        'text' => 'Updated comment',
    ]);
    
    expect($validator->fails())->toBeFalse();
});

