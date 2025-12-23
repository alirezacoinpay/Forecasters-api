<?php

use App\Jobs\PredictionResolved;
use App\Models\Prediction;
use App\Models\PredictionOption;
use App\Models\UserPrediction;
use App\Models\UserPredictionPoints;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

test('handle calculates and inserts prediction points', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    $correctOption = PredictionOption::factory()->make(['id' => 1, 'is_true' => true]);
    $prediction->setRelation('predictionTrueOption', $correctOption);
    
    $userPrediction1 = UserPrediction::factory()->make(['id' => 1, 'user_id' => 1, 'prediction_option_id' => 1]);
    $userPrediction2 = UserPrediction::factory()->make(['id' => 2, 'user_id' => 2, 'prediction_option_id' => 1]);
    
    $prediction->setRelation('userPredictions', collect([$userPrediction1, $userPrediction2]));
    
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('where')->with('prediction_option_id', 1)->once()->andReturnSelf();
    $query->shouldReceive('count')->once()->andReturn(2);
    
    $prediction->shouldReceive('userPredictions')->once()->andReturn($query);
    $prediction->shouldReceive('getAttribute')->with('userPredictions')->andReturn(collect([$userPrediction1, $userPrediction2]));
    
    $userPrediction1->shouldReceive('calculatePoints')->with($prediction, 2)->once()->andReturn(10);
    $userPrediction2->shouldReceive('calculatePoints')->with($prediction, 2)->once()->andReturn(15);
    
    $pointsQuery = Mockery::mock(Builder::class);
    UserPredictionPoints::shouldReceive('query')->once()->andReturn($pointsQuery);
    $pointsQuery->shouldReceive('insert')->with([
        ['user_id' => 1, 'points' => 10],
        ['user_id' => 2, 'points' => 15],
    ])->once()->andReturn(true);
    
    $job = new PredictionResolved($prediction);
    $job->handle();
    
    expect(true)->toBeTrue();
});

