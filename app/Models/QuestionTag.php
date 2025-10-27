<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionTag extends Model
{
    public const string TAG = "question";
    protected $fillable = [
        'tag_id',
        'question_id',
    ];

    public $timestamps = false;

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
