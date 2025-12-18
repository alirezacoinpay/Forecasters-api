<?php

namespace App\Observers;

use App\Enums\ActivityAction;
use App\Models\PredictionForward;
use App\Services\ActivityLogger\ActivityLogger;

class PredictionForwardObserver
{
    /**
     * Handle the PredictionForward "created" event.
     */
    public function created(PredictionForward $predictionForward): void
    {

        //todo add the metadata
        ActivityLogger::log(
            $predictionForward->user_id,
            ActivityAction::SHARE,
            $predictionForward->prediction,
            []
        );

    }

    /**
     * Handle the PredictionForward "updated" event.
     */
    public function updated(PredictionForward $predictionForward): void
    {
        //
    }

    /**
     * Handle the PredictionForward "deleted" event.
     */
    public function deleted(PredictionForward $predictionForward): void
    {
        //
    }

    /**
     * Handle the PredictionForward "restored" event.
     */
    public function restored(PredictionForward $predictionForward): void
    {
        //
    }

    /**
     * Handle the PredictionForward "force deleted" event.
     */
    public function forceDeleted(PredictionForward $predictionForward): void
    {
        //
    }
}
