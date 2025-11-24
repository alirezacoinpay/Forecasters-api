<?php

namespace App\Console\Commands;

use App\Services\OpenAI\OpenAIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;
use OpenAI;

class GenerateGeneralForecasts extends Command
{
    protected $signature = 'app:generate-general-forecasts';

    protected $description = 'Generate general forecasts';
    public function __construct(
       protected OpenAIService $openAIService,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {

        Log::info('GenerateGeneralForecasts:handle');
        $this->openAIService->generateGeneralForecasts();

        return CommandAlias::SUCCESS;
    }
}
