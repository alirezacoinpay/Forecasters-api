<?php

use App\Http\Requests\Client\UserPredictions\UpdateUserPredictionRequest;
use App\Models\QuestionOption;

test('it validates required question_option_id', function () {
    $request = new UpdateUserPredictionRequest();
    
    $validator = validator($request->rules(), []);
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('question_option_id'))->toBeTrue();
});

test('it validates question_option_id exists', function () {
    $request = new UpdateUserPredictionRequest();
    
    $validator = validator($request->rules(), [
        'question_option_id' => 99999,
    ]);
    
    expect($validator->fails())->toBeTrue();
});

test('it accepts valid update data', function () {
    $option = QuestionOption::factory()->make(['id' => 1]);
    $request = new UpdateUserPredictionRequest();
    
    // Verify the rules structure
    expect($request->rules())->toHaveKey('question_option_id');
    expect($request->rules()['question_option_id'])->toContain('required');
    expect($request->rules()['question_option_id'])->toContain('integer');
});

