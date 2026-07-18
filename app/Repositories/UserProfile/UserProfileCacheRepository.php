<?php

namespace App\Repositories\UserProfile;

use App\Models\UserProfile;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class UserProfileCacheRepository extends BaseCacheRepository implements UserProfileRepositoryInterface
{
    private string $tag = UserProfile::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_userprofile_id_',
       'findByIdLight' => 'single_light_userprofile_id_',
       'all' => 'all_userprofiless_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        UserProfileRepository $repository,
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
    public function findByUserId($userId)
    {
        $key = $this->generateKey([$userId]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($userId) {
          return  $this->repository->findByUserId($userId);
        });
    }

}
