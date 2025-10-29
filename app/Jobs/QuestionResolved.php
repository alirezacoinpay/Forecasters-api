<?php

namespace App\Jobs;

use App\Models\Question;
use App\Models\UserPredictionPoints;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class QuestionResolved implements ShouldQueue
{
    use Queueable;


    public function __construct(
        protected Question $question,
    )
    {
        //
    }


    public function handle(): void
    {
        $userPredictions = $this->question->userPredictions;
        $data = [];
        $correctQuestionOptionId = $this->question->questionTrueOption();
        $correctPredictionsCount = $this->question->userPredictions()->where('question_option_id', $correctQuestionOptionId)->count();
        foreach ($userPredictions as $userPrediction) {
            $data[] = [
                'user_id' => $userPrediction->user_id,
                'points' => $userPrediction->calculatePoints($this->question, $correctPredictionsCount),

            ];
        }
        $result = UserPredictionPoints::query()
            ->insert($data);

    }
}
