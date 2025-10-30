<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ActivityAction;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_id',
        'subject_type',
        'metadata',
        'session_id',
        'device_type',
        'platform',
    ];

    public $timestamps = false;

    protected $casts = [
        'action' => ActivityAction::class,
        'metadata' => 'json',
        'created_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
