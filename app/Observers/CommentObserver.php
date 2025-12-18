<?php

namespace App\Observers;

use App\Enums\ActivityAction;
use App\Models\Comment;
use App\Services\ActivityLogger\ActivityLogger;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        if ($comment->parent_id){
            ActivityLogger::log(
                $comment->user_id,
                ActivityAction::COMMENT_REPLY,
                $comment
            );
        }
        ActivityLogger::log(
            $comment->user_id,
            ActivityAction::COMMENT,
            $comment->prediction
        );
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
