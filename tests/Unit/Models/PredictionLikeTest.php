<?php

use App\Models\PredictionLike;
use App\Models\User;
use App\Models\UserPrediction;

test('predictionLike has user relationship', function () {
    $like = PredictionLike::factory()->make(['id' => 1]);
    
    $relationship = $like->user();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('predictionLike has userPrediction relationship', function () {
    $like = PredictionLike::factory()->make(['id' => 1]);
    
    $relationship = $like->userPrediction();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

