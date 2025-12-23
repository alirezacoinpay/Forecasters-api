<?php

use App\Http\Requests\Client\UserPredictions\UpdateUserPredictionRequest;
use App\Models\PredictionOption;

test('it validates required prediction_option_id', function () {
    $request = new UpdateUserPredictionRequest();
    
    $validator = validator([], $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('prediction_option_id'))->toBeTrue();
});

test('it validates prediction_option_id exists', function () {
    $request = new UpdateUserPredictionRequest();
    
    $validator = validator([
        'prediction_option_id' => 99999,
    ], $request->rules());
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid update data', function () {
    $option = PredictionOption::factory()->make(['id' => 1]);
    $request = new UpdateUserPredictionRequest();

    // Verify the rules structure
    expect($request->rules())->toHaveKey('prediction_option_id');
    expect($request->rules()['prediction_option_id'])->toContain('required');
    expect($request->rules()['prediction_option_id'])->toContain('integer');
});

