<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends BaseModel
{
    use SoftDeletes;
    public const string TAG = "question";
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

    public function questionForwards(): HasMany
    {
        return $this->hasMany(QuestionForward::class);
    }

    public function questionOptions(): HasMany
    {
        return $this->hasMany(QuestionOption::class );
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
        return $this->belongsToMany(Tag::class, 'question_tags');
    }

    public function userPredictions(): HasManyThrough
    {
        return $this->hasManyThrough(
            UserPrediction::class,
            QuestionOption::class,
            'question_id',
            'question_option_id',
            'id',
            'id'
        );
    }

}
