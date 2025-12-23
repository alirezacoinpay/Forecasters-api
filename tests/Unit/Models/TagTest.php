<?php

use App\Models\Tag;
use App\Models\Prediction;
use Tests\TestCase;

uses(TestCase::class);

test('tag has predictions relationship', function () {
    $tag = Tag::factory()->make(['id' => 1]);
    
    $relationship = $tag->predictions();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});

