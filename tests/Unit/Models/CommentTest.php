<?php

use App\Models\Comment;
use App\Models\CommentLike;
use Tests\TestCase;

uses(TestCase::class);

test('comment has commentLikes relationship', function () {
    $comment = Comment::factory()->make(['id' => 1]);

    $relationship = $comment->commentLikes();

    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('comment has myCommentLike relationship', function () {
    $comment = Comment::factory()->make(['id' => 1]);

    $relationship = $comment->userLike();

    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});

test('comment has parent relationship', function () {
    $comment = Comment::factory()->make(['id' => 1]);

    $relationship = $comment->parent();

    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('comment has children relationship', function () {
    $comment = Comment::factory()->make(['id' => 1]);

    $relationship = $comment->children();

    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

