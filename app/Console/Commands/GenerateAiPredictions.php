<?php

namespace App\Console\Commands;

use App\Jobs\GeneratePredictionJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class GenerateAiPredictions extends Command
{
    protected $signature = 'app:generate-ai-predictions';

    protected $description = 'automatically generate ai-predictions';


    public function handle()
    {
        $params = [
            'language'     => 'Persian',
            'region'       => 'iran',
        ];

        $userId = Auth::id();

        GeneratePredictionJob::dispatch($params, $userId);
    }
}
