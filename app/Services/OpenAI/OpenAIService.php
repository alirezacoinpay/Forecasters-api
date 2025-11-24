<?php
namespace App\Services\OpenAI;
use Illuminate\Support\Facades\Log;
use OpenAI;
use OpenAI\Contracts\ClientContract;
class OpenAIService
{
    protected ClientContract $client;

    public function __construct()
    {

        $this->client = \OpenAI::factory()
            ->withApiKey(config('services.openai.api_key'))
            ->withBaseUri(config('services.openai.base_url'))
            ->make();
    }

    public function GenerateGeneralForecasts(?string $prompt = null): array
    {
        $prompt = $prompt ?? config('ai.prompts.general_forecasts');
        $schema = config('ai.openai_schema');
        $instructions = config('ai.openai_instructions.general');

        $response = $this->client->responses()->create([
            'model' => 'openai/gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => (string) $instructions
                ],
                [
                    'role' => 'user',
                    'content' => (string) $prompt
                ]
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $schema
            ]
        ]);

        Log::error('OpenAIService:GenerateGeneralForecasts',[
            'response' => $response,
        ]);

        return json_decode($response['choices'][0]['message']['content'], true);
    }

}
