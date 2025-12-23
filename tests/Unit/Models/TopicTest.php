<?php

use App\Models\Topic;
use App\Models\Prediction;

test('topic has predictions relationship', function () {
    $topic = Topic::factory()->make(['id' => 1]);
    
    $relationship = $topic->predictions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

