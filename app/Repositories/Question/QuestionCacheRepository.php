<?php

namespace App\Repositories\Question;

use App\Models\Question;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class QuestionCacheRepository extends BaseCacheRepository implements QuestionRepositoryInterface
{
    private string $tag = Question::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_question_id_',
       'findQuestionOptionById' => 'single_question_option_id_',
       'findQuestionOptionByIdLight' => 'single_light_question_option_id_',
       'findByIdLight' => 'single_light_question_id_',
       'findFeedPage' => 'single_feed_page_question_id_',
       'userFeedQuestions' => 'user_feed_page_questionss_',
       'all' => 'all_questionss_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        QuestionRepository $repository,
    )
    {
        parent::__construct($repository);
    }

    public function findQuestionOptionById($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findQuestionOptionById($id);
        });
    }

    public function userFeedQuestion($id, $userId= null)
    {
        $key = $this->generateKey([$id, $userId]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id, $userId) {
           return $this->repository->userFeedQuestion($id, $userId);
        });
    }
    public function findQuestionOptionByIdLight($id)
    {
        $key = $this->generateKey([$id]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id) {
           return $this->repository->findQuestionOptionByIdLight($id);
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

    public function userFeedQuestions($userId = null, $params = [])
    {
        $key = $this->generateKey([$userId = null, $params]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($userId, $params) {
          return  $this->repository->userFeedQuestions($userId, $params);
        });
    }

}
