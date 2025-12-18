<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel
{
    public const string TAG = "category";
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
