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

}
