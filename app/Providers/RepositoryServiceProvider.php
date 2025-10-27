<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\{
    Category\CategoryCacheRepository,
    Category\CategoryRepository,
    Category\CategoryRepositoryInterface,
    Comment\CommentCacheRepository,
    Comment\CommentRepository,
    Comment\CommentRepositoryInterface,
    QuestionForward\QuestionForwardCacheRepository,
    QuestionForward\QuestionForwardRepository,
    QuestionForward\QuestionForwardRepositoryInterface,
    Question\QuestionCacheRepository,
    Question\QuestionRepository,
    Question\QuestionRepositoryInterface,
    Tag\TagCacheRepository,
    Tag\TagRepository,
    Tag\TagRepositoryInterface,
    Topic\TopicCacheRepository,
    Topic\TopicRepository,
    Topic\TopicRepositoryInterface,
    UserPrediction\UserPredictionCacheRepository,
    UserPrediction\UserPredictionRepository,
    UserPrediction\UserPredictionRepositoryInterface,
    User\UserCacheRepository,
    User\UserRepository,
    User\UserRepositoryInterface,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (env('DATABASE_CACHE', true)) {
            $this->bindRepository(CategoryRepositoryInterface::class, CategoryRepository::class, CategoryCacheRepository::class);
            $this->bindRepository(CommentRepositoryInterface::class, CommentRepository::class, CommentCacheRepository::class);
            $this->bindRepository(QuestionRepositoryInterface::class, QuestionRepository::class, QuestionCacheRepository::class);
            $this->bindRepository(TagRepositoryInterface::class, TagRepository::class, TagCacheRepository::class);
            $this->bindRepository(TopicRepositoryInterface::class, TopicRepository::class, TopicCacheRepository::class);
            $this->bindRepository(UserPredictionRepositoryInterface::class, UserPredictionRepository::class, UserPredictionCacheRepository::class);
            $this->bindRepository(UserRepositoryInterface::class, UserRepository::class, UserCacheRepository::class);
        }else{
            $this->bindRepository(CategoryRepositoryInterface::class, CategoryRepository::class, CategoryRepository::class);
            $this->bindRepository(CommentRepositoryInterface::class, CommentRepository::class, CommentRepository::class);
            $this->bindRepository(QuestionRepositoryInterface::class, QuestionRepository::class, QuestionRepository::class);
            $this->bindRepository(TagRepositoryInterface::class, TagRepository::class, TagRepository::class);
            $this->bindRepository(TopicRepositoryInterface::class, TopicRepository::class, TopicRepository::class);
            $this->bindRepository(UserPredictionRepositoryInterface::class, UserPredictionRepository::class, UserPredictionRepository::class);
            $this->bindRepository(UserRepositoryInterface::class, UserRepository::class, UserRepository::class);
        }
    }


    public function boot(): void
    {
        //
    }
    private function bindRepository(string $interface, string $plainRepository, string $cacheRepository): void
    {
        $this->app->bind($interface, function ($app) use ($plainRepository, $cacheRepository) {
            return env('CACHE_DB', false) ? $app->make($cacheRepository) : $app->make($plainRepository);
        });
    }
}
