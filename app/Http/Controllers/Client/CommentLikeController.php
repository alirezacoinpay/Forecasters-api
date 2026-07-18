<?php

namespace App\Http\Controllers\Client;

use App\Enums\ActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\CommentResource;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Services\ActivityLogger\ActivityLogger;
use Illuminate\Http\JsonResponse;

class CommentLikeController extends Controller
{
    public function __construct(
        protected CommentRepositoryInterface $repository,
    ) {}

    public function toggle($id): JsonResponse
    {
        $userId = auth()->id();

        if (!$userId) {
            return $this->error('api.unauthorized', [], 401);
        }

        $comment = $this->repository->findByIdLight($id);

        if (!$comment) {
            return $this->error('api.not_found.comment', [], 404);
        }

        $isLiked = $this->repository->toggleCommentLike($id, $userId);

        if ($isLiked === null) {
            return $this->error('api.error.like_toggle', [], 500);
        }

        // Log activity
        ActivityLogger::log(
            $userId,
            $isLiked ? ActivityAction::COMMENT_LIKE : ActivityAction::UNLIKE,
            $comment
        );
        $comment = $this->repository->findById($id);

        return $this->success(new CommentResource($comment));
    }
}

