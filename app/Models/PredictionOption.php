<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PredictionOption extends BaseModel
{
    public const string TAG = "prediction";
    protected $table = 'prediction_options';
    
    protected $fillable = [
        'title',
        'prediction_id',
        'is_true',
    ];

    public $timestamps = false;

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }

    public function myPrediction(): HasOne
    {
        return $this->hasOne(UserPrediction::class)->select('id')->where('user_id', auth()->id());
    }

    public function userPredictions(): HasMany
    {
        return $this->hasMany(UserPrediction::class);
    }
}
