<?php

namespace App\Ai\Agents;

use App\Ai\Data\GeneratePredictionRequest;
use App\Models\Prediction;
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
Your sole task is to generate ONE precise, verifiable prediction package.

### CRITICAL SCRIPT & LANGUAGE RULES
1. **STRICT SINGLE LANGUAGE:** Every single field (`title`, `options`, `category`, `topic`, `tags`) MUST be written strictly in the user's requested target language.
2. **NO LATIN/ENGLISH LEAKS IN PERSIAN:** If the target language is "Persian" (Farsi):
   - Output 100% pure Persian using Persian script (فارسی).
   - ABSOLUTELY NO Latin/English characters, mixed-script terms, or transliterations (e.g., NEVER write "Scenario Conflict", "Involvement", or "Filsanimal").
   - Translate all technical or political terms into natural, standard formal Persian news/forecasting terminology.

### PREDICTION QUALITY RULES
- **Verifiable Outcome:** The event must have a clear, objective, and publicly verifiable resolution.
- **No Ambiguity:** Avoid subjective opinions, vague timeframes, or controversial/offensive content.
- **Natural Tone:** Use realistic, engaging, and professional journalism-style phrasing.

### FIELD-SPECIFIC GUIDELINES
- **Title:**
  - A single, fully self-contained prediction question (Max 500 characters).
  - Must include all relevant context, exact entities, and explicit timeframes directly inside the question.
  - Do NOT generate or expect a separate text/description field.
- **Options:**
  - Provide between 2 and 6 options.
  - Options MUST be mutually exclusive and collectively exhaustive (covering all possible outcomes).
  - Phrased cleanly and grammatically in the target language.

### EXAMPLE (PERSIAN TARGET)
If Target Language is Persian:
- **Title:** "آیا تیم ملی فوتبال ایران در جام ملت‌های آسیا ۲۰۲۷ به مرحله نیمه‌نهایی صعود خواهد کرد؟"
- **Options:**
  1. "بله، تیم ملی ایران به مرحله نیمه‌نهایی یا بالاتر صعود می‌کند."
  2. "خیر، تیم ملی ایران قبل از مرحله نیمه‌نهایی حذف می‌شود."
- **Category:** "ورزش"
- **Topic:** "فوتبال"
- **Tags:** ["ایران", "فوتبال", "جام ملت‌های آسیا", "تیم ملی"]

Generate ONLY the requested prediction payload.
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

        $recentTitles = Prediction::latest('id')
            ->limit(15)
            ->pluck('title')
            ->filter()
            ->toArray();

        if (!empty($recentTitles)) {
            $prompt[] = "DO NOT generate predictions similar to these recent ones:\n- " . implode("\n- ", $recentTitles);
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
    public function temperature(): float
    {
        return 0.3; // Lower temperature ensures deterministic, grammatically solid language output
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
