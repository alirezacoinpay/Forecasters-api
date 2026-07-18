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
    PredictionForward\PredictionForwardCacheRepository,
    PredictionForward\PredictionForwardRepository,
    PredictionForward\PredictionForwardRepositoryInterface,
    Prediction\PredictionCacheRepository,
    Prediction\PredictionRepository,
    Prediction\PredictionRepositoryInterface,
    Tag\TagCacheRepository,
    Tag\TagRepository,
    Tag\TagRepositoryInterface,
    Topic\TopicCacheRepository,
    Topic\TopicRepository,
    Topic\TopicRepositoryInterface,
    UserPrediction\UserPredictionCacheRepository,
    UserPrediction\UserPredictionRepository,
    UserPrediction\UserPredictionRepositoryInterface,
    UserProfile\UserProfileCacheRepository,
    UserProfile\UserProfileRepository,
    UserProfile\UserProfileRepositoryInterface,
    UserSearchHistory\UserSearchHistoryCacheRepository,
    UserSearchHistory\UserSearchHistoryRepository,
    UserSearchHistory\UserSearchHistoryRepositoryInterface,
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
        $this->bindRepository(UserProfileRepositoryInterface::class, UserProfileRepository::class, UserProfileCacheRepository::class);
        $this->bindRepository(CategoryRepositoryInterface::class, CategoryRepository::class, CategoryCacheRepository::class);
        $this->bindRepository(CommentRepositoryInterface::class, CommentRepository::class, CommentCacheRepository::class);
        $this->bindRepository(PredictionRepositoryInterface::class, PredictionRepository::class, PredictionCacheRepository::class);
        $this->bindRepository(PredictionForwardRepositoryInterface::class, PredictionForwardRepository::class, PredictionForwardCacheRepository::class);
        $this->bindRepository(TagRepositoryInterface::class, TagRepository::class, TagCacheRepository::class);
        $this->bindRepository(TopicRepositoryInterface::class, TopicRepository::class, TopicCacheRepository::class);
        $this->bindRepository(UserPredictionRepositoryInterface::class, UserPredictionRepository::class, UserPredictionCacheRepository::class);
        $this->bindRepository(UserRepositoryInterface::class, UserRepository::class, UserCacheRepository::class);
        $this->bindRepository(UserSearchHistoryRepositoryInterface::class, UserSearchHistoryRepository::class, UserSearchHistoryCacheRepository::class);

    }


    public function boot(): void
    {
        //
    }
    private function bindRepository(string $interface, string $plainRepository, string $cacheRepository): void
    {

        if (env('DATABASE_CACHE', true)){

            $this->app->bind($interface, function ($app) use ($plainRepository, $cacheRepository) {
                return env('CACHE_DB', false) ? $app->make($cacheRepository) : $app->make($plainRepository);
            });
        }else{

            $this->app->bind($interface, function ($app) use ($plainRepository, $cacheRepository) {
                return env('CACHE_DB', false) ? $app->make($cacheRepository) : $app->make($plainRepository);
            });
        }

    }
}
