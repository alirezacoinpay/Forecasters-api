<?php

use App\Models\PredictionLike;
use App\Models\User;
use App\Models\UserPrediction;
use Tests\TestCase;

uses(TestCase::class);

test('predictionLike has user relationship', function () {
    $like = PredictionLike::factory()->make(['id' => 1]);
    
    $relationship = $like->user();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('predictionLike has prediction relationship', function () {
    $like = PredictionLike::factory()->make(['id' => 1]);
    
    $relationship = $like->prediction();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

