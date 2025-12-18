<?php

use App\Models\Tag;
use App\Models\Question;

test('tag has question relationship', function () {
    $tag = Tag::factory()->make(['id' => 1]);
    
    $relationship = $tag->question();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});

