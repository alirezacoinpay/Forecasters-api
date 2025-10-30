<?php
namespace App\Services\ActivityLogger;

use App\Enums\ActivityAction;
use App\Jobs\LogActivityJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(
        ?int $userId,
        ActivityAction $action,
        Model $subject,
        array $metadata = []
    ): void {
        if (! $userId) {
            return;
        }

        LogActivityJob::dispatch([
            'user_id'      => $userId,
            'action'       => $action,
            'subject_id'   => $subject->getKey(),
            'subject_type'=> get_class($subject),
            'metadata'     => $metadata,
            'session_id'   => session()->getId(),
            'device_type'  => Request::header('device-type'),
            'platform'     => Request::header('X-Platform'),
        ]);
    }
}
