<?php

use App\Http\Resources\Client\UserPredictionResource;
use App\Models\UserPrediction;

test('it transforms prediction to array', function () {
    $prediction = UserPrediction::factory()->make([
        'id' => 1,
        'user_id' => 1,
        'prediction_option_id' => 1,
        'percentage' => 100,
    ]);

    $resource = new UserPredictionResource($prediction);
    $array = $resource->toArray(request());

    expect($array)->toHaveKeys(['user_id', 'prediction_option_id', 'percentage']);
    expect($array['percentage'])->toBe(100);
});

test('it includes likesCount when loaded', function () {
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    $prediction->setAttribute('prediction_likes_count', 3);

    $resource = new UserPredictionResource($prediction);
    $array = $resource->toArray(request());

    expect($array)->toHaveKey('likesCount');
    expect($array['likesCount'])->toBe(3);
});

test('it includes isLiked when myPredictionLike loaded', function () {
    $prediction = UserPrediction::factory()->make(['id' => 1]);
    $like = \App\Models\PredictionLike::factory()->make();
    $prediction->setRelation('myPredictionLike', $like);

    $resource = new UserPredictionResource($prediction);
    $array = $resource->toArray(request());

    expect($array)->toHaveKey('isLiked');
    expect($array['isLiked'])->toBeTrue();
});

