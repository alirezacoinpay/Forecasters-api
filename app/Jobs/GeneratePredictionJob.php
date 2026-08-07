<?php

namespace App\Jobs;

use App\Services\Prediction\PredictionAiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GeneratePredictionJob implements ShouldQueue
{
    use Queueable;
    /**
     * Timeout for long-running LLM tasks.
     */
    public int $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $params = [],
        public ?int $userId = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(PredictionAiService $predictionService): void
    {
        $predictionService->generateAndSave($this->params, $this->userId);
    }
}
