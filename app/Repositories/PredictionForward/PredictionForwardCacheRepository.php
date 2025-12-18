<?php

namespace App\Repositories\PredictionForward;

use App\Models\PredictionForward;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class PredictionForwardCacheRepository extends BaseCacheRepository implements PredictionForwardRepositoryInterface
{
    private string $tag = PredictionForward::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_predictionforward_id_',
       'findByIdLight' => 'single_light_predictionforward_id_',
       'all' => 'all_predictionforwards_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        PredictionForwardRepository $repository,
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
