<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class UserCacheRepository extends BaseCacheRepository implements UserRepositoryInterface
{
    private string $tag = User::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_user_id_',
       'findByIdLight' => 'single_light_user_id_',
       'findByMobileLight' => 'single_light_user_mobile_',
       'all' => 'all_userss_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        UserRepository $repository,
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

    public function findByMobileLight($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findByMobileLight($id);
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
