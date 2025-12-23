<?php

use App\Models\Comment;
use App\Models\CommentLike;
use App\Repositories\Comment\CommentRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

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
    
    Auth::shouldReceive('check')->andReturn(false);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($comment);
});

test('findById includes myCommentLike when authenticated', function () {
    $comment = Mockery::mock(Comment::class);
    $query = Mockery::mock(Builder::class);
    
    $this->model->shouldReceive('withTrashed')->once()->andReturn($query);
    $query->shouldReceive('withCount')->with('commentLikes')->once()->andReturn($query);
    $query->shouldReceive('with')->with(Mockery::on(function ($arg) {
        return is_array($arg) && isset($arg['myCommentLike']) && is_callable($arg['myCommentLike']);
    }))->once()->andReturn($query);
    $query->shouldReceive('find')->with(1)->once()->andReturn($comment);
    
    Auth::shouldReceive('check')->andReturn(true);
    Auth::shouldReceive('id')->andReturn(1);
    
    $result = $this->repository->findById(1);
    
    expect($result)->toBe($comment);
});

test('toggleCommentLike creates like when not exists', function () {
    // Static model method mocking is difficult in unit tests
    // This functionality is tested in feature tests
    $this->markTestSkipped('Static model method mocking requires database connection - tested in feature tests');
});

test('toggleCommentLike deletes like when exists', function () {
    // Static model method mocking is difficult in unit tests
    // This functionality is tested in feature tests
    $this->markTestSkipped('Static model method mocking requires database connection - tested in feature tests');
});

test('toggleCommentLike returns null when comment not found', function () {
    $this->model->shouldReceive('find')->with(999)->once()->andReturn(null);
    
    $result = $this->repository->toggleCommentLike(999, 1);
    
    expect($result)->toBeNull();
});

