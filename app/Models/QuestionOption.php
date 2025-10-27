<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuestionOption extends BaseModel
{
    public const string TAG = "question";
    protected $fillable = [
        'title',
        'question_id',
        'is_true',
    ];

    public $timestamps = false;

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
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
