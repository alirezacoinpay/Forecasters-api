<?php

use App\Models\PredictionOption;
use App\Models\UserPrediction;

test('predictionOption has prediction relationship', function () {
    $option = PredictionOption::factory()->make(['id' => 1]);
    
    $relationship = $option->prediction();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('predictionOption has userPredictions relationship', function () {
    $option = PredictionOption::factory()->make(['id' => 1]);
    
    $relationship = $option->userPredictions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('predictionOption has myPrediction relationship', function () {
    $option = PredictionOption::factory()->make(['id' => 1]);
    
    $relationship = $option->myPrediction();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

