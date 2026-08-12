<?php
namespace App\Filament\Pages;

use App\Services\Prediction\PredictionImporterService;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class ImportPredictions extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static string|null|\UnitEnum $navigationGroup = 'Predictions';
    protected static ?string $navigationLabel = 'Import Predictions';

    protected string $view = 'filament.pages.import-predictions';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'language' => 'English',
            'region'   => 'Global',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                Section::make('Configuration')
                    ->columns(2)
                    ->schema([
                        Select::make('language')
                            ->options([
                                'English' => 'English',
                                'Persian' => 'Persian',
                            ])
                            ->required(),

                        Select::make('region')
                            ->options([
                                'Global' => 'Global',
                                'US'     => 'United States',
                                'IR'     => 'Iran',
                            ])
                            ->required(),
                    ]),

                Section::make('JSON Payload')
                    ->description('Provide predictions by pasting JSON directly or uploading a JSON file.')
                    ->schema([
                        FileUpload::make('json_file')
                            ->label('Upload JSON File')
                            ->acceptedFileTypes(['application/json', 'text/plain'])
                            ->disk('local')
                            ->directory('temp-imports'),

                        Textarea::make('json_text')
                            ->label('Or Paste Raw JSON')
                            ->placeholder('{"predictions": [...]}')
                            ->rows(12),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(PredictionImporterService $importer): void
    {
        $formData = $this->form->getState();

        $payload = null;

        // 1. Prefer uploaded file if present
        if (!empty($formData['json_file'])) {
            $payload = Storage::disk('local')->get($formData['json_file']);
        } elseif (!empty($formData['json_text'])) {
            $payload = $formData['json_text'];
        }

        if (empty($payload)) {
            Notification::make()
                ->warning()
                ->title('No data provided')
                ->body('Please upload a file or paste JSON content.')
                ->send();

            return;
        }

        try {
            $imported = $importer->import(
                payload: $payload,
                language: $formData['language'],
                region: $formData['region']
            );

            Notification::make()
                ->success()
                ->title('Import Successful')
                ->body("Successfully imported {$imported->count()} prediction(s).")
                ->send();

            $this->form->fill([
                'language'  => $formData['language'],
                'region'    => $formData['region'],
                'json_text' => null,
                'json_file' => null,
            ]);

        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title('Import Failed')
                ->body($e->getMessage())
                ->send();
        }
    }
}
