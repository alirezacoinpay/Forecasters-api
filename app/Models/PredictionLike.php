<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PredictionLike extends BaseModel
{
    public const string TAG = "user_prediction";

    protected $fillable = [
        'user_id',
        'question_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}

