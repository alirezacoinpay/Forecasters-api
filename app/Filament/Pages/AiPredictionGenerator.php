<?php

namespace App\Filament\Pages;

use App\Ai\Agents\PredictionGenerator;
use App\Ai\Data\GeneratePredictionRequest;
use App\Enums\CategoryStatus;
use App\Enums\TopicStatus;
use App\Jobs\GeneratePredictionJob;
use App\Models\Category;
use App\Models\Prediction;
use App\Models\Tag;
use App\Models\Topic;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Schemas\Schema;

class AiPredictionGenerator extends Page implements HasForms
{

    use InteractsWithForms;
    public ?string $language = 'English';

    public ?string $region = 'Global';

    public ?string $topic = null;

    public ?string $category = null;

    public ?string $instructions = null;

    public int $count = 1;

    public array $generated = [];

    protected string $view = 'filament.pages.ai-prediction-generator';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('language')
                    ->options([
                        'English' => 'English',
                        'Persian' => 'Persian',
                    ]),

                TextInput::make('region'),

                TextInput::make('topic'),

                TextInput::make('category'),

                Textarea::make('instructions'),

                TextInput::make('count')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100),
            ]);
    }
    public function generate(): void
    {
        $params = [
            'language'     => $this->language,
            'region'       => $this->region,
            'topic'        => $this->topic,
            'category'     => $this->category,
            'instructions' => $this->instructions,
        ];

        $userId = Auth::id();

        // Dispatch background jobs to process without HTTP timeout
        for ($i = 0; $i < $this->count; $i++) {
            GeneratePredictionJob::dispatch($params, $userId);
        }

        Notification::make()
            ->title("Queued {$this->count} prediction job(s)!")
            ->body('The AI is generating predictions in the background.')
            ->success()
            ->send();
    }
}
