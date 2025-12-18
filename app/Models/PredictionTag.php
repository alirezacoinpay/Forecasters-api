<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PredictionTag extends Model
{
    public const string TAG = "prediction";
    protected $table = 'prediction_tags';
    
    protected $fillable = [
        'tag_id',
        'prediction_id',
    ];

    public $timestamps = false;

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }
}
