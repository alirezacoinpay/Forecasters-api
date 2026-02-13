<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class UserSearchHistory extends BaseModel
{
    public const string TAG = "userSearchHistory";
    use HasFactory;

    protected $fillable = [
        'user_id',
        'searchable_type',
        'searchable_id',
        'search_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function searchable(): MorphTo
    {
        return $this->morphTo();
    }
}
