<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Comment extends BaseModel
{
    public const string TAG = "comment";
    public const string FILE_PATH = "comments";

    protected $fillable = [
        'user_id',
        'parent_id',
        'question_id',
        'text',
        'file',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function usersLikedComment(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, CommentLike::class);
    }
}
