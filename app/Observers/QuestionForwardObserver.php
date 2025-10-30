<?php

namespace App\Observers;

use App\Enums\ActivityAction;
use App\Models\QuestionForward;
use App\Services\ActivityLogger\ActivityLogger;

class QuestionForwardObserver
{
    /**
     * Handle the QuestionForward "created" event.
     */
    public function created(QuestionForward $questionForward): void
    {

        //todo add the metadata
        ActivityLogger::log(
            $questionForward->user_id,
            ActivityAction::SHARE,
            $questionForward->question,
            []
        );

    }

    /**
     * Handle the QuestionForward "updated" event.
     */
    public function updated(QuestionForward $questionForward): void
    {
        //
    }

    /**
     * Handle the QuestionForward "deleted" event.
     */
    public function deleted(QuestionForward $questionForward): void
    {
        //
    }

    /**
     * Handle the QuestionForward "restored" event.
     */
    public function restored(QuestionForward $questionForward): void
    {
        //
    }

    /**
     * Handle the QuestionForward "force deleted" event.
     */
    public function forceDeleted(QuestionForward $questionForward): void
    {
        //
    }
}
