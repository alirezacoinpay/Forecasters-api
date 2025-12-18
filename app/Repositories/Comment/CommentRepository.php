<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{

    protected Model $model;
    public function __construct(
         Comment $model,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        $query = $this->model->withTrashed();
        
        if (auth()->check()) {
            $userId = auth()->id();
            $query->withCount('commentLikes')
                  ->with(['myCommentLike' => function ($q) use ($userId) {
                      $q->where('user_id', $userId);
                  }]);
        } else {
            $query->withCount('commentLikes');
        }
        
        return $query->find($id);
    }


    public function all($params = [])
    {
        $query = $this->model->newQuery();



        if (isset($params['trashed'])) {
            if ($params['trashed']) {
                $query->onlyTrashed();
            }
        }else{
            $query->withTrashed();
        }

        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

    public function toggleCommentLike($commentId, $userId)
    {
        $comment = $this->model->find($commentId);
        
        if (!$comment) {
            return null;
        }

        $existingLike = CommentLike::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return false; // Unliked
        } else {
            CommentLike::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
            ]);
            return true; // Liked
        }
    }

    public function findByIdWithLikes($id, $userId = null)
    {
        $query = $this->model->withTrashed()
            ->withCount('commentLikes');

        if ($userId) {
            $query->with(['myCommentLike' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }

        return $query->find($id);
    }

}
