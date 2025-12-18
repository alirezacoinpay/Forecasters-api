<?php

use App\Models\Topic;
use App\Models\Question;

test('topic has question relationship', function () {
    $topic = Topic::factory()->make(['id' => 1]);
    
    $relationship = $topic->question();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

