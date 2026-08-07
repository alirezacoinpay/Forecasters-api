<?php

namespace App\Filament\Pages;

use App\Ai\Agents\PredictionGenerator;
use App\Ai\Data\GeneratePredictionRequest;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Ai;
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
        set_time_limit(300);
        $this->generated = [];

        for ($i = 0; $i < $this->count; $i++) {

            $agent = new PredictionGenerator(new GeneratePredictionRequest(
                language: $this->language,
                region: $this->region,
                topic: $this->topic,
                category: $this->category,
                additionalInstructions: $this->instructions,
            ));

            $response = $agent->prompt('Generate a prediction.');

            Log::info('AiPredictionGenerator:generate', [
                'response' => $response,
            ]);

            // Convert the object/response to a native array so Livewire can dehydrate it
            $this->generated[] = method_exists($response, 'toArray')
                ? $response->toArray()
                : (array) $response;
        }
    }
}
