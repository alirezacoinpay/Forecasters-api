<?php

use App\Models\Prediction;
use App\Models\Comment;
use App\Models\PredictionOption;
use App\Models\Tag;
use Tests\TestCase;

uses(TestCase::class);

test('prediction has user relationship', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->user();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('prediction has predictionOptions relationship', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->predictionOptions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('prediction has comments relationship', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->comments();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('prediction has tags relationship', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->tags();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
});

test('prediction has predictionTrueOption relationship', function () {
    $prediction = Prediction::factory()->make(['id' => 1]);
    
    $relationship = $prediction->predictionTrueOption();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

