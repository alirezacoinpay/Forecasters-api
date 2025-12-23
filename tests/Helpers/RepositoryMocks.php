<?php

namespace Tests\Helpers;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use App\Repositories\PredictionForward\PredictionForwardRepositoryInterface;
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
     * Create a mock PredictionRepository
     */
    public static function predictionRepository(): Mockery\MockInterface
    {
        return Mockery::mock(PredictionRepositoryInterface::class);
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
     * Create a mock PredictionForwardRepository
     */
    public static function predictionForwardRepository(): Mockery\MockInterface
    {
        return Mockery::mock(PredictionForwardRepositoryInterface::class);
    }

    /**
     * Create a mock UserRepository
     */
    public static function userRepository(): Mockery\MockInterface
    {
        return Mockery::mock(UserRepositoryInterface::class);
    }
}

