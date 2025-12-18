<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\BaseCacheRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class CommentCacheRepository extends BaseCacheRepository implements CommentRepositoryInterface
{
    private string $tag = Comment::TAG;
    private int $timeToLive = 86000;

    protected array $prefixes = [
       'findById' => 'single_comment_id_',
       'findByIdLight' => 'single_light_comment_id_',
       'findByIdWithLikes' => 'single_comment_with_likes_id_',
       'all' => 'all_commentss_',
    ];

    protected BaseRepository $repository;
    public function __construct(
        CommentRepository $repository,
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

    public function toggleCommentLike($commentId, $userId)
    {
        // Don't cache toggle operations - they modify data
        return $this->repository->toggleCommentLike($commentId, $userId);
    }

    public function findByIdWithLikes($id, $userId = null)
    {
        $key = $this->generateKey([$id, $userId]);

        return Cache::tags($this->tag)->remember($key, $this->timeToLive, function () use ($id, $userId) {
           return $this->repository->findByIdWithLikes($id, $userId);
        });
    }

}
