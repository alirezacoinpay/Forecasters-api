<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends BaseModel
{
    public const string TAG = "tag";
    protected $fillable = [
        'title',
        'color',
    ];

    public $timestamps = false;

    public function predictions(): HasManyThrough
    {
        return $this->hasManyThrough(Prediction::class, PredictionTag::class);
    }
    public function userSearchHistories(): MorphMany
    {
        return $this->morphMany(UserSearchHistory::class, 'searchable');
    }
}
