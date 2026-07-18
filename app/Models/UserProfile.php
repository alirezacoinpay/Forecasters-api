<?php

namespace App\Models;

use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends BaseModel
{
    use HasFile;
    public const string TAG = "userprofile";

    public const string FILE_PATH = "user-profiles";

    protected $fillable = [
        'avatar',
        'name',
        'user_id',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
