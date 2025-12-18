<?php

use App\Models\Comment;
use App\Models\CommentLike;
use App\Repositories\Comment\CommentRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

beforeEach(function () {
    $this->model = Mockery::mock(Comment::class);
    $this->repository = new CommentRepository($this->model);
});

test('findById returns comment with trashed', function () {
    $comment = Mockery::mock(Comment::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with('commentLikes')->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($comment);
    
    auth()->shouldReceive('check')->andReturn(false);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($comment);
});

test('findById includes myCommentLike when authenticated', function () {
    $comment = Mockery::mock(Comment::class);
    $query = Mockery::mock(Builder::class);
    $likeQuery = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with('commentLikes')->once()->andReturn($query);
    $query->shouldReceive('with')->with(Mockery::on(function ($callback) {
        return is_callable($callback);
    }))->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($comment);
    
    auth()->shouldReceive('check')->andReturn(true);
    auth()->shouldReceive('id')->andReturn(1);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($comment);
});

test('toggleCommentLike creates like when not exists', function () {
    $comment = Mockery::mock(Comment::class);
    $comment->shouldReceive('getAttribute')->with('id')->andReturn(1);
    
    $this->model->shouldReceive('find')->with(1)->once()->andReturn($comment);
    
    CommentLike::shouldReceive('where')->with('comment_id', 1)->once()
        ->andReturnSelf();
    CommentLike::shouldReceive('where')->with('user_id', 1)->once()
        ->andReturnSelf();
    CommentLike::shouldReceive('first')->once()->andReturn(null);
    
    CommentLike::shouldReceive('create')->with([
        'comment_id' => 1,
        'user_id' => 1,
    ])->once()->andReturn(true);
    
    $result = $this->repository->toggleCommentLike(1, 1);
    
    expect($result)->toBeTrue();
});

test('toggleCommentLike deletes like when exists', function () {
    $comment = Mockery::mock(Comment::class);
    $existingLike = Mockery::mock(CommentLike::class);
    
    $this->model->shouldReceive('find')->with(1)->once()->andReturn($comment);
    
    CommentLike::shouldReceive('where')->with('comment_id', 1)->once()
        ->andReturnSelf();
    CommentLike::shouldReceive('where')->with('user_id', 1)->once()
        ->andReturnSelf();
    CommentLike::shouldReceive('first')->once()->andReturn($existingLike);
    
    $existingLike->shouldReceive('delete')->once()->andReturn(true);
    
    $result = $this->repository->toggleCommentLike(1, 1);
    
    expect($result)->toBeFalse();
});

test('toggleCommentLike returns null when comment not found', function () {
    $this->model->shouldReceive('find')->with(999)->once()->andReturn(null);
    
    $result = $this->repository->toggleCommentLike(999, 1);
    
    expect($result)->toBeNull();
});

