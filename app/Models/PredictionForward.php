<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PredictionForward extends BaseModel
{
    public const string TAG = "prediction_forward";
    protected $table = 'prediction_forwards';

    protected $fillable = [
        'prediction_id',
        'user_id',
        'target',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class );
    }

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class );
    }

}
