<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class CategoryCacheRepository extends BaseCacheRepository implements CategoryRepositoryInterface
{
    private string $tag = Category::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_category_id_',
       'findByIdLight' => 'single_light_category_id_',
       'allFeedPage' => 'all_feed_page_categoriess_',
       'all' => 'all_categoriess_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        CategoryRepository $repository,
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

    public function allFeedPage($params = [])
    {
        $key = $this->generateKey([$params]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($params) {
          return  $this->repository->allFeedPage($params);
        });
    }

}
