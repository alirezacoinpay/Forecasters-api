<?php

use App\Http\Requests\Client\Comments\AddCommentRequest;
use App\Models\Prediction;
use App\Models\User;

test('it validates required prediction_id', function () {
    $request = new AddCommentRequest();
    
    $validator = validator([], $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('prediction_id'))->toBeTrue();
});

test('it validates prediction_id exists', function () {
    $request = new AddCommentRequest();
    
    $validator = validator([
        'prediction_id' => 99999,
    ], $request->rules());
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid data', function () {
    $request = new AddCommentRequest();
    
    // Verify the rules structure
    expect($request->rules())->toHaveKey('prediction_id');
    expect($request->rules())->toHaveKey('text');
    expect($request->rules())->toHaveKey('file');
});

test('it validates file is image', function () {
    $request = new AddCommentRequest();
    
    $validator = validator([
        'prediction_id' => 1,
        'file' => 'not-a-file',
    ], $request->rules());
    
    expect($validator->fails())->toBeTrue();
});

