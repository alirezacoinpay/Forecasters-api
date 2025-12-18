<?php

namespace App\Jobs;

use App\Models\Prediction;
use App\Models\UserPredictionPoints;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PredictionResolved implements ShouldQueue
{
    use Queueable;


    public function __construct(
        protected Prediction $prediction,
    )
    {
        //
    }


    public function handle(): void
    {
        $userPredictions = $this->prediction->userPredictions;
        $data = [];
        $correctPredictionOptionId = $this->prediction->predictionTrueOption();
        $correctPredictionsCount = $this->prediction->userPredictions()->where('prediction_option_id', $correctPredictionOptionId)->count();
        foreach ($userPredictions as $userPrediction) {
            $data[] = [
                'user_id' => $userPrediction->user_id,
                'points' => $userPrediction->calculatePoints($this->prediction, $correctPredictionsCount),

            ];
        }
        $result = UserPredictionPoints::query()
            ->insert($data);

    }
}
