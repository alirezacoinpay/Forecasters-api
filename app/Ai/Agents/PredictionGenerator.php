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

CRITICAL LANGUAGE RULE:
- You MUST write the title, options, category, topic, and tags ENTIRELY in the target language requested by the user.
- If the language requested is "Persian" (or Farsi), output all strings using Persian script/alphabet.

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

Prediction Title Rules:
- The title MUST contain the full prediction question and essential context.
- Maximum 500 characters.
- Clear, concise, and complete.
- Include a timeframe and specific conditions directly within the title.
- Do NOT generate a separate description or text section.

Options Rules:
- Between 2 and 6 options.
- Options must be mutually exclusive.
- Options must cover every possible outcome.

Only generate the prediction itself.
PROMPT;
    }

    public function messages(): iterable
    {
        $lang = $this->request->language ?? 'English';

        $prompt = [
            "CRITICAL: Generate ALL output text (title, options, category, topic, tags) strictly in: {$lang}.",
        ];

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
            $prompt[] = "Additional Instructions: {$this->request->additionalInstructions}";
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
                ->description('The full, self-contained prediction question (max 500 characters) including all context and timeframe.')
                ->required(),

            'options' => $schema
                ->array($schema->string())
                ->description('List of options in the requested language.')
                ->min(2)
                ->max(6)
                ->required(),

            'tags' => $schema
                ->array($schema->string())
                ->description('Array of keywords/tags in the requested language.')
                ->min(1)
                ->max(6)
                ->required(),

            'category' => $schema
                ->string()
                ->description('Category name in the requested language.')
                ->required(),

            'topic' => $schema
                ->string()
                ->description('Topic name in the requested language.')
                ->required(),
        ];
    }
}
