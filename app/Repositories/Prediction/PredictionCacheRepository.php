<?php

namespace App\Repositories\Prediction;

use App\Models\Prediction;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class PredictionCacheRepository extends BaseCacheRepository implements PredictionRepositoryInterface
{
    private string $tag = Prediction::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_prediction_id_',
       'findPredictionOptionById' => 'single_prediction_option_id_',
       'findPredictionOptionByIdLight' => 'single_light_prediction_option_id_',
       'findByIdLight' => 'single_light_prediction_id_',
       'findFeedPage' => 'single_feed_page_prediction_id_',
       'userFeedPredictions' => 'user_feed_page_predictions_',
       'userSearchPredictions' => 'user_search_page_predictions_',
       'all' => 'all_predictions_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        PredictionRepository $repository,
    )
    {
        parent::__construct($repository);
    }

    public function findPredictionOptionById($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findPredictionOptionById($id);
        });
    }

    public function userFeedPrediction($id, $userId= null)
    {
        $key = $this->generateKey([$id, $userId]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id, $userId) {
           return $this->repository->userFeedPrediction($id, $userId);
        });
    }
    public function findPredictionOptionByIdLight($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findPredictionOptionByIdLight($id);
        });
    }

    public function findByIdLight($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findByIdLight($id);
        });
    }

    public function findFeedPage($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findFeedPage($id);
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

    public function userFeedPredictions($userId = null, $params = [])
    {
        $key = $this->generateKey([$userId = null, $params]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($userId, $params) {
          return  $this->repository->userFeedPredictions($userId, $params);
        });
    }
    public function predictionComments($userId, $params = [])
    {
        $key = $this->generateKey([$userId, $params]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($userId, $params) {
          return  $this->repository->predictionComments($userId, $params);
        });
    }
    public function userSearchPredictions($userId = null, $params = [])
    {
        $key = $this->generateKey([$userId = null, $params]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($userId, $params) {
          return  $this->repository->userSearchPredictions($userId, $params);
        });
    }

    public function insertPredictionOptions($data)
    {
        $this->repository->insertPredictionOptions($data);
    }

}
