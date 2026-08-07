<?php

namespace App\Ai\Agents;

use App\Ai\Data\GeneratePredictionRequest;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;

class PredictionGenerator implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    public function __construct(
        protected GeneratePredictionRequest $request
    ) {}

    public function instructions(): string
    {
        return <<<'PROMPT'
You are an expert prediction writer for the Forecasters platform.

Your only job is to generate ONE high-quality prediction.

Rules:
- Generate exactly one prediction.
- The prediction must describe a future event.
- It must have a clear and objectively verifiable outcome.
- Avoid subjective or opinion-based questions.
- Avoid duplicate ideas.
- Avoid vague wording.
- Avoid controversial or offensive content.
- Use natural language.
- Make the prediction interesting to a broad audience.

Prediction title rules:
- Maximum 120 characters.
- Clear and concise.
- Include a timeframe when necessary.

Description rules:
- Explain the prediction briefly.
- One or two short paragraphs.
- Do not repeat the title.

Options:
- Between 2 and 6 options.
- Options must be mutually exclusive.
- Options must cover every possible outcome.

Only generate the prediction itself.
PROMPT;
    }

    public function messages(): iterable
    {
        $prompt = [];

        if ($this->request->language) {
            $prompt[] = "Language: {$this->request->language}";
        }

        if ($this->request->region) {
            $prompt[] = "Region: {$this->request->region}";
        }

        if ($this->request->topic) {
            $prompt[] = "Topic: {$this->request->topic}";
        }

        if ($this->request->category) {
            $prompt[] = "Category: {$this->request->category}";
        }

        if ($this->request->additionalInstructions) {
            $prompt[] = $this->request->additionalInstructions;
        }

        return [
            new UserMessage(implode("\n", $prompt))
        ];
    }

    public function tools(): iterable
    {
        return [];
    }

    public function schema($schema): array
    {
        return [
            'title' => $schema
                ->string()
                ->description('The concise title of the prediction.')
                ->required(),

            'description' => $schema
                ->string()
                ->description('Brief explanation of the prediction.')
                ->required(),

            'options' => $schema
                ->array($schema->string()) // Specify array item type
                ->min(2)
                ->max(6)
                ->required(),

            'topic' => $schema
                ->string()
                ->nullable(),

            'category' => $schema
                ->string()
                ->nullable(),
        ];
    }
}
