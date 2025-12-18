<?php
namespace App\Observers;

use App\Enums\ActivityAction;
use App\Models\Prediction;
use App\Services\ActivityLogger\ActivityLogger;

class PredictionObserver
{
    /**
     * Handle the Prediction "created" event.
     */
    public function created(Prediction $prediction): void
    {
        if ($prediction->user_id){
            ActivityLogger::log(
                $prediction->user_id,
                ActivityAction::PREDICTION_CREATE,
                $prediction
            );
        }
    }


    public function updated(Prediction $prediction): void
    {
        if ($prediction->user_id){

            ActivityLogger::log(
                $prediction->user_id,
                ActivityAction::PREDICTION_EDIT,
                $prediction
            );
        }
    }


    public function deleted(Prediction $prediction): void
    {
        if ($prediction->user_id){
            ActivityLogger::log(
                $prediction->user_id,
                ActivityAction::PREDICTION_DELETE,
                $prediction
            );
        }
    }

    /**
     * Handle the Prediction "restored" event.
     */
    public function restored(Prediction $prediction): void
    {
        //
    }

    /**
     * Handle the Prediction "force deleted" event.
     */
    public function forceDeleted(Prediction $prediction): void
    {
        //
    }
}
