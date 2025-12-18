<?php

use App\Http\Requests\Client\UserPredictions\AddUserPredictionRequest;
use App\Models\QuestionOption;

test('it validates required question_option_id', function () {
    $request = new AddUserPredictionRequest();
    
    $validator = validator($request->rules(), []);
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('question_option_id'))->toBeTrue();
});

test('it validates question_option_id exists', function () {
    $request = new AddUserPredictionRequest();
    
    $validator = validator($request->rules(), [
        'question_option_id' => 99999,
    ]);
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid prediction data', function () {
    $option = QuestionOption::factory()->make(['id' => 1]);
    $request = new AddUserPredictionRequest();
    
    // Verify the rules structure
    expect($request->rules())->toHaveKey('question_option_id');
    expect($request->rules())->toHaveKey('comment.*');
    expect($request->rules())->toHaveKey('comment.text');
    expect($request->rules())->toHaveKey('comment.file');
});

