<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prediction extends BaseModel
{
    use SoftDeletes;
    public const string TAG = "prediction";
    protected $table = 'predictions';

    protected $fillable = [
        'title',
        'text',
        'category_id',
        'topic_id',
        'user_id',
        'closes_at',
        'starts_at',
        'resolve_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function predictionForwards(): HasMany
    {
        return $this->hasMany(PredictionForward::class);
    }

    public function predictionLikes(): HasMany
    {
        return $this->hasMany(PredictionLike::class);
    }

    public function predictionOptions(): HasMany
    {
        return $this->hasMany(PredictionOption::class );
    }

    public function predictionTrueOption(): HasOne
    {
        return $this->hasOne(PredictionOption::class)
            ->where('is_true', true);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'prediction_tags',     // pivot table name
            'prediction_id',       // foreign key on pivot table referencing Prediction
            'tag_id'               // foreign key on pivot table referencing Tag
        );
    }
    public function userPrediction(): HasOneThrough
    {
        return $this->hasOneThrough(
            UserPrediction::class,
            PredictionOption::class,
            'prediction_id',
            'prediction_option_id',
            'id',
            'id'
        );
    }

    public function userPredictions(): HasManyThrough
    {
        return $this->hasManyThrough(
            UserPrediction::class,
            PredictionOption::class,
            'prediction_id',
            'prediction_option_id',
            'id',
            'id'
        );
    }

    public function correctUserPredictions(): HasManyThrough
    {
        return $this->hasManyThrough(
            UserPrediction::class,
            PredictionOption::class,
            'prediction_id',
            'prediction_option_id',
            'id',
            'id'
        );
    }

    public function predictionForecasters(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,              // Final model we want
            UserPrediction::class,    // Intermediate model
            'prediction_option_id',     // Foreign key on UserPrediction table...
            'id',                     // Foreign key on User table...
            'id',                     // Local key on Prediction table...
            'user_id'                 // Local key on UserPrediction table...
        )->whereIn(
            'user_predictions.prediction_option_id',
            $this->predictionOptions()->pluck('id')
        );
    }


}
