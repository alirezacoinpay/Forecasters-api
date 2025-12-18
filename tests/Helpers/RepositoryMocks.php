<?php

namespace Tests\Helpers;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\QuestionForward\QuestionForwardRepositoryInterface;
use App\Repositories\Tag\TagRepositoryInterface;
use App\Repositories\Topic\TopicRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserPrediction\UserPredictionRepositoryInterface;
use Mockery;

class RepositoryMocks
{
    /**
     * Create a mock CategoryRepository
     */
    public static function categoryRepository(): Mockery\MockInterface
    {
        return Mockery::mock(CategoryRepositoryInterface::class);
    }

    /**
     * Create a mock TopicRepository
     */
    public static function topicRepository(): Mockery\MockInterface
    {
        return Mockery::mock(\App\Repositories\Topic\TopicRepositoryInterface::class);
    }

    /**
     * Create a mock QuestionRepository
     */
    public static function questionRepository(): Mockery\MockInterface
    {
        return Mockery::mock(QuestionRepositoryInterface::class);
    }

    /**
     * Create a mock CommentRepository
     */
    public static function commentRepository(): Mockery\MockInterface
    {
        return Mockery::mock(CommentRepositoryInterface::class);
    }

    /**
     * Create a mock UserPredictionRepository
     */
    public static function userPredictionRepository(): Mockery\MockInterface
    {
        return Mockery::mock(UserPredictionRepositoryInterface::class);
    }

    /**
     * Create a mock TagRepository
     */
    public static function tagRepository(): Mockery\MockInterface
    {
        return Mockery::mock(TagRepositoryInterface::class);
    }

    /**
     * Create a mock QuestionForwardRepository
     */
    public static function questionForwardRepository(): Mockery\MockInterface
    {
        return Mockery::mock(QuestionForwardRepositoryInterface::class);
    }

    /**
     * Create a mock UserRepository
     */
    public static function userRepository(): Mockery\MockInterface
    {
        return Mockery::mock(UserRepositoryInterface::class);
    }
}

