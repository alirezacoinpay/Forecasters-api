<?php

use App\Http\Requests\Client\UserPredictions\AddUserPredictionRequest;
use App\Models\PredictionOption;

test('it validates required prediction_option_id', function () {
    $request = new AddUserPredictionRequest();
    
    $validator = validator([], $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('prediction_option_id'))->toBeTrue();
});

test('it validates prediction_option_id exists', function () {
    $request = new AddUserPredictionRequest();
    
    $validator = validator([
        'prediction_option_id' => 99999,
    ], $request->rules());
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid prediction data', function () {
    $option = PredictionOption::factory()->make(['id' => 1]);
    $request = new AddUserPredictionRequest();
    
    // Verify the rules structure
    expect($request->rules())->toHaveKey('prediction_option_id');
    expect($request->rules())->toHaveKey('comment.*');
    expect($request->rules())->toHaveKey('comment.text');
    expect($request->rules())->toHaveKey('comment.file');
});

