<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
        );    }
}
