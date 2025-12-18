<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepositoryInterface;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function toggleCommentLike($commentId, $userId);
    public function findByIdWithLikes($id, $userId = null);
}
