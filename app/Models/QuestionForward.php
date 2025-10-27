<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionForward extends BaseModel
{
    public const string TAG = "question_forward";

    protected $fillable = [
        'question_id',
        'user_id',
        'target',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class );
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class );
    }

}
