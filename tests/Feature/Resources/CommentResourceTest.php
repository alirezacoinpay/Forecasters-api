<?php

use App\Http\Resources\Client\CommentResource;
use App\Models\Comment;

test('it transforms comment to array', function () {
    $comment = Comment::factory()->make([
        'id' => 1,
        'text' => 'Test comment',
        'user_id' => 1,
        'question_id' => 1,
    ]);
    
    $resource = new CommentResource($comment);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKeys(['user_id', 'question_id', 'text', 'time_past']);
    expect($array['text'])->toBe('Test comment');
});

test('it includes likesCount when loaded', function () {
    $comment = Comment::factory()->make(['id' => 1]);
    $comment->setAttribute('comment_likes_count', 5);
    
    $resource = new CommentResource($comment);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKey('likesCount');
    expect($array['likesCount'])->toBe(5);
});

test('it includes isLiked when myCommentLike loaded', function () {
    $comment = Comment::factory()->make(['id' => 1]);
    $like = \App\Models\CommentLike::factory()->make();
    $comment->setRelation('myCommentLike', $like);
    
    $resource = new CommentResource($comment);
    $array = $resource->toArray(request());
    
    expect($array)->toHaveKey('isLiked');
    expect($array['isLiked'])->toBeTrue();
});

