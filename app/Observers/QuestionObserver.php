<?php
namespace App\Observers;

use App\Enums\ActivityAction;
use App\Models\Question;
use App\Services\ActivityLogger\ActivityLogger;

class QuestionObserver
{
    /**
     * Handle the Question "created" event.
     */
    public function created(Question $question): void
    {
        if ($question->user_id){
            ActivityLogger::log(
                $question->user_id,
                ActivityAction::QUESTION_CREATE,
                $question
            );
        }
    }


    public function updated(Question $question): void
    {
        if ($question->user_id){

            ActivityLogger::log(
                $question->user_id,
                ActivityAction::QUESTION_EDIT,
                $question
            );
        }
    }


    public function deleted(Question $question): void
    {
        if ($question->user_id){
            ActivityLogger::log(
                $question->user_id,
                ActivityAction::QUESTION_DELETE,
                $question
            );
        }
    }

    /**
     * Handle the Question "restored" event.
     */
    public function restored(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "force deleted" event.
     */
    public function forceDeleted(Question $question): void
    {
        //
    }
}
