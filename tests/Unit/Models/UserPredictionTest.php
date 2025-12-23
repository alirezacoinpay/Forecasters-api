<?php

use App\Models\UserPrediction;
use App\Models\PredictionLike;
use App\Models\Prediction;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

uses(TestCase::class);

test('userPrediction has predictionLikes relationship', function () {
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->predictionLikes();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('userPrediction has myPredictionLike relationship', function () {
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->myPredictionLike();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

test('calculatePoints returns penalty for wrong answer', function () {
    Config::set('forecast_points.penalty_score', -2);
    
    $userPrediction = UserPrediction::factory()->make(['prediction_option_id' => 1]);
    $prediction = Prediction::factory()->make(['id' => 1]);
    $correctOption = Mockery::mock();
    $correctOption->id = 2;
    $prediction->setRelation('predictionTrueOption', $correctOption);
    
    $points = $userPrediction->calculatePoints($prediction, 0);
    
    expect($points)->toBe(-2);
});

test('calculatePoints calculates positive points for correct answer', function () {
    Config::set('forecast_points.total_budget', 30);
    Config::set('forecast_points.factor_weights', [
        'time' => 33.33,
        'difficulty' => 33.33,
        'popularity' => 33.34,
    ]);
    Config::set('forecast_points.factor_min_score', 1.0);
    Config::set('forecast_points.popularity_k', 100);
    
    $userPrediction = UserPrediction::factory()->make([
        'prediction_option_id' => 1,
        'updated_at' => now(),
    ]);
    $prediction = Prediction::factory()->make([
        'id' => 1,
        'starts_at' => now()->subDays(1),
        'resolve_at' => now()->addDays(1),
    ]);
    $correctOption = Mockery::mock();
    $correctOption->id = 1;
    $prediction->setRelation('predictionTrueOption', $correctOption);
    $prediction->setRelation('userPredictions', collect([$userPrediction]));
    
    $points = $userPrediction->calculatePoints($prediction, 1);
    
    expect($points)->toBeGreaterThan(0);
});

