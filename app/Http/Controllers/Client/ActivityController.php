<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ActivityLogs\LogActivitiesRequest;
use App\Jobs\LogActivityJob;
use Illuminate\Http\JsonResponse;

class ActivityController extends Controller
{
    public function __invoke(LogActivitiesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        LogActivityJob::dispatch([
            'user_id' => auth()->id(),
            'action' => $validated['action'],
            'subject_id' => $validated['subject_id'] ?? null,
            'subject_type' => $validated['subject_type'] ?? null,
            'metadata' => $validated['metadata'] ?? [],
            'session_id' => session()->getId(),
            'device_type' => $request->header('device-type'),
            'platform' => $request->header('X-Platform'),
        ]);

        return $this->success([], 'api.success.log');
    }
}
