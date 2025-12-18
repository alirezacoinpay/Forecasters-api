<?php

use App\Services\OpenAI\OpenAIService;
use Illuminate\Support\Facades\Config;
use Mockery;
use ReflectionClass;

beforeEach(function () {
    // Set required config values before any test runs
    Config::set('services.openai.api_key', 'test-key');
    Config::set('services.openai.base_url', 'https://test.com');
    Config::set('ai.prompts.general_forecasts', 'Test prompt');
    Config::set('ai.openai_schema', ['type' => 'object']);
    Config::set('ai.openai_instructions.general', 'Test instructions');
});

test('GenerateGeneralForecasts calls OpenAI client with correct parameters', function () {
    // Mock the OpenAI client
    $client = Mockery::mock(\OpenAI\Contracts\ClientContract::class);
    $responses = Mockery::mock();
    $message = (object)['content' => json_encode(['forecasts' => []])];
    
    $responses->shouldReceive('create')
        ->once()
        ->andReturn([
            'choices' => [
                ['message' => $message]
            ]
        ]);
    
    $client->shouldReceive('responses')->once()->andReturn($responses);
    
    // Create service instance and inject mocked client via reflection
    $service = new OpenAIService();
    $reflection = new ReflectionClass(OpenAIService::class);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $client);
    
    $result = $service->GenerateGeneralForecasts();
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('forecasts');
});
