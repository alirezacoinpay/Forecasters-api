<?php

use App\Models\UserPrediction;
use App\Models\PredictionLike;
use App\Models\Question;
use Illuminate\Support\Facades\Config;

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
    
    $prediction = UserPrediction::factory()->make(['question_option_id' => 1]);
    $question = Question::factory()->make(['id' => 1]);
    $correctOption = Mockery::mock();
    $correctOption->id = 2;
    $question->setRelation('questionTrueOption', $correctOption);
    
    $points = $prediction->calculatePoints($question, 0);
    
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
    
    $prediction = UserPrediction::factory()->make([
        'question_option_id' => 1,
        'updated_at' => now(),
    ]);
    $question = Question::factory()->make([
        'id' => 1,
        'starts_at' => now()->subDays(1),
        'resolve_at' => now()->addDays(1),
    ]);
    $correctOption = Mockery::mock();
    $correctOption->id = 1;
    $question->setRelation('questionTrueOption', $correctOption);
    $question->setRelation('userPredictions', collect([$prediction]));
    
    $points = $prediction->calculatePoints($question, 1);
    
    expect($points)->toBeGreaterThan(0);
});

