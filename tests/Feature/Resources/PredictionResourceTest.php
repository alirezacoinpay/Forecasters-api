<?php

use App\Http\Resources\Client\PredictionResource;
use App\Models\Prediction;

test('it transforms prediction to array', function () {
    $prediction = Prediction::factory()->make([
        'id' => 1,
        'title' => 'Test Prediction',
        'text' => 'Prediction text',
    ]);
    
    $resource = new PredictionResource($prediction);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKeys(['id', 'title', 'text', 'time_past']);
    expect($array['title'])->toBe('Test Prediction');
});

test('it includes counts when loaded', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    $prediction->setAttribute('user_predictions_count', 10);
    $prediction->setAttribute('comments_count', 5);
    
    $resource = new PredictionResource($prediction);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKey('userPredictionsCount');
    expect($array)->toHaveKey('commentsCount');
});

