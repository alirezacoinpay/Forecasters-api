<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Config;
use phpDocumentor\Reflection\Types\Integer;

class UserPrediction extends BaseModel
{
    public const string TAG = "user_prediction";

    protected $fillable = [
        'user_id',
        'question_option_id',
        'percentage',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }

    public function question(): HasOneThrough
    {
        return $this->hasOneThrough(
            Question::class,
            QuestionOption::class,
            'id',
            'id',
            'question_option_id',
            'question_id'
        );
    }

    public function userPredictionPoint(): HasOne
    {
        return $this->hasOne(UserPrediction::class);
    }
    public function calculatePoints($question, $correctPredictionsCount): int
    {
        // -----------------------------------------------------------------
        // 1. Load config
        // -----------------------------------------------------------------
        $totalBudget   = Config::get('forecast_points.total_budget', 30);
        $weights       = Config::get('forecast_points.factor_weights', [
            'time'       => 33.33,
            'difficulty' => 33.33,
            'popularity' => 33.34,
        ]);

        // Minimum per factor = 1 point (so total min = 3)
        $factorMinScore  = Config::get('forecast_points.factor_min_score', 1.0);
        $penaltyScore  = Config::get('forecast_points.penalty_score', -2.0);

          /*  that is a number that by that count of forecasters participating in this question
            the point of populations get at half of the max point and
            when 10x of that count participating the point goes up to 90% of the maximum point and
            when 100X of that count participating the point goes up to 99% of the maximum point
          */
        $kPopularity   = Config::get('forecast_points.popularity_k', 100);

        // Validate weights sum to 100%
        $sum = array_sum($weights);
        if (abs($sum - 100) > 0.01) {
            throw new \RuntimeException("Forecast weights must sum to 100%, got {$sum}%");
        }


        $timeMaxScore       = $totalBudget * $weights['time'] / 100;
        $difficultyMaxScore       = $totalBudget * $weights['difficulty'] / 100;
        $populationMaxScore        = $totalBudget * $weights['popularity'] / 100;

        // -----------------------------------------------------------------
        // 2. Wrong answer → 0
        // -----------------------------------------------------------------
        if ($this->question_option_id != $question->questionTrueOption->id) {
            return $penaltyScore;
        }

        // -----------------------------------------------------------------
        // 3. TIME FACTOR
        // -----------------------------------------------------------------
        $resolveDate = Carbon::make($question->resolve_at);
        $totalHours  = $resolveDate->diffInHours($question->starts_at);
        $leftHours   = $resolveDate->diffInHours($this->forecasted_at ?? $this->updated_at);
        if ($leftHours == 0) {
            $timeScore = $factorMinScore;
        }else{

            $timeRatio = $totalHours == 0 ? 1.0 : ($leftHours / $totalHours);
            $timeMultiplier  = pow($timeRatio, 2); // 0 → 1

            $timeScore = $timeMaxScore * $timeMultiplier;
        }


        // -----------------------------------------------------------------
        // 4. DIFFICULTY FACTOR
        // -----------------------------------------------------------------
        $totalForecastCount = $question->userPredictions->count();
        $difficultyScore  = $factorMinScore;

        if ($totalForecastCount > 0) {
            $correctPredictionsPercentage = ($correctPredictionsCount / $totalForecastCount) * 100;
            $diffMult   = 1 + log10(100 / max($correctPredictionsPercentage, 1)); // 1 → 3
            $normalized = min($diffMult / 3, 1);               // 0 → 1
            $difficultyScore  = $factorMinScore + ($difficultyMaxScore - $factorMinScore) * $normalized;
        }

        // -----------------------------------------------------------------
        // 5. POPULARITY FACTOR
        // -----------------------------------------------------------------
        $popMultRaw = ($totalForecastCount / ($totalForecastCount + $kPopularity)); // 0 → 1
        $popScore   = $factorMinScore + ($populationMaxScore - $factorMinScore) * $popMultRaw;

        // -----------------------------------------------------------------
        // 6. FINAL TOTAL
        // -----------------------------------------------------------------
        $total = $timeScore + $difficultyScore + $popScore;

        return (int) round($total); // e.g. 3 → 30
    }
}
