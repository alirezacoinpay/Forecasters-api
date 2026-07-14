<?php

namespace App\Repositories\UserPrediction;

use App\Models\UserPrediction;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class UserPredictionCacheRepository extends BaseCacheRepository implements UserPredictionRepositoryInterface
{
    private string $tag = UserPrediction::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_userprediction_id_',
       'findByIdLight' => 'single_light_userprediction_id_',
       'findByIdWithLikes' => 'single_userprediction_with_likes_id_',
       'all' => 'all_userpredictionss_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        UserPredictionRepository $repository,
    )
    {
        parent::__construct($repository);
    }

    public function findByIdLight($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findByIdLight($id);
        });
    }
    public function findById($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findById($id);
        });
    }

    public function all($params = [])
    {
        $key = $this->generateKey([$params]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($params) {
          return  $this->repository->all($params);
        });
    }

    public function togglePredictionLike($predictionId, $userId)
    {
        // Don't cache toggle operations - they modify data
        return $this->repository->togglePredictionLike($predictionId, $userId);
    }

    public function findByIdWithLikes($id, $userId = null)
    {
        $key = $this->generateKey([$id, $userId]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id, $userId) {
           return $this->repository->findByIdWithLikes($id, $userId);
        });
    }
    public function findByPredictionAndUser($userId, $predictionId)
    {
        $key = $this->generateKey([$userId, $predictionId]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($userId, $predictionId) {
           return $this->repository->findByPredictionAndUser($userId, $predictionId);
        });
    }

}
