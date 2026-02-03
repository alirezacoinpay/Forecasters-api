<?php

namespace App\Repositories\UserSearchHistory;

use App\Models\UserSearchHistory;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class UserSearchHistoryCacheRepository extends BaseCacheRepository implements UserSearchHistoryRepositoryInterface
{
    private string $tag = UserSearchHistory::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_usersearchhistory_id_',
       'findByIdLight' => 'single_light_usersearchhistory_id_',
       'all' => 'all_usersearchhistoriess_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        UserSearchHistoryRepository $repository,
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


    public function storeFromFeed($userId, $params): void
    {
        $this->repository->storeFromFeed($userId, $params);
    }

}
