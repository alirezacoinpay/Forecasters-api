<?php

use App\Models\CommentLike;
use App\Models\User;
use App\Models\Comment;

test('commentLike has user relationship', function () {
    $like = CommentLike::factory()->make(['id' => 1]);
    
    $relationship = $like->user();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('commentLike has comment relationship', function () {
    $like = CommentLike::factory()->make(['id' => 1]);
    
    $relationship = $like->comment();
    
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

