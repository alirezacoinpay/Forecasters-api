<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends BaseModel
{
    public const string TAG = "topic";
    protected $fillable = [
        'title',
        'icon',
        'status',
    ];

    public $timestamps = false;

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }
}
