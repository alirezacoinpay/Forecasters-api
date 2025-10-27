<?php

namespace App\Repositories\Topic;

use App\Models\Topic;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class TopicCacheRepository extends BaseCacheRepository implements TopicRepositoryInterface
{
    private string $tag = Topic::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_topic_id_',
       'findByIdLight' => 'single_light_topic_id_',
       'all' => 'all_topicss_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        TopicRepository $repository,
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
