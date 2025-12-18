<?php

use App\Models\QuestionOption;
use App\Models\UserPrediction;

test('questionOption has question relationship', function () {
    $option = QuestionOption::factory()->make(['id' => 1]);
    
    $relationship = $option->question();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('questionOption has userPredictions relationship', function () {
    $option = QuestionOption::factory()->make(['id' => 1]);
    
    $relationship = $option->userPredictions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('questionOption has myPrediction relationship', function () {
    $option = QuestionOption::factory()->make(['id' => 1]);
    
    $relationship = $option->myPrediction();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

