<?php

namespace App\Observers;

use App\Enums\ActivityAction;
use App\Models\UserPrediction;
use App\Services\ActivityLogger\ActivityLogger;

class UserPredictionObserver
{
    /**
     * Handle the UserPrediction "created" event.
     */
    public function created(UserPrediction $userPrediction): void
    {
        ActivityLogger::log(
            $userPrediction->user_id,
            ActivityAction::PREDICT,
            $userPrediction->question
        );
    }

    /**
     * Handle the UserPrediction "updated" event.
     */
    public function updated(UserPrediction $userPrediction): void
    {
        ActivityLogger::log(
            $userPrediction->user_id,
            ActivityAction::PREDICTION_CHANGE,
            $userPrediction->question
        );
    }

    /**
     * Handle the UserPrediction "deleted" event.
     */
    public function deleted(UserPrediction $userPrediction): void
    {
        //
    }

    /**
     * Handle the UserPrediction "restored" event.
     */
    public function restored(UserPrediction $userPrediction): void
    {
        //
    }

    /**
     * Handle the UserPrediction "force deleted" event.
     */
    public function forceDeleted(UserPrediction $userPrediction): void
    {
        //
    }
}
