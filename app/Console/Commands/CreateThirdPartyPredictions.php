<?php

namespace App\Console\Commands;

use App\Jobs\FetchThirdPartyPrediction;
use App\Jobs\GeneratePredictionJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class CreateThirdPartyPredictions extends Command
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

        FetchThirdPartyPrediction::dispatch();
    }
}
