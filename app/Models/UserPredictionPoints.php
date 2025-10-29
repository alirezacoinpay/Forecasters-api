<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPredictionPoints extends Model
{
    public const string TAG = "user_prediction";

    protected $fillable = [
        'user_prediction_id',
        'points',
    ];

    public function userPrediction(): BelongsTo
    {
        return $this->belongsTo(UserPrediction::class);
    }

}
