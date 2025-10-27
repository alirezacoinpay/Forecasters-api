<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Filament\Resources\Questions\QuestionResource;
use App\Filament\Resources\Questions\Widgets\UserPredictionChart;
use App\Filament\Resources\Questions\Widgets\UserPredictionOverTimeChart;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuestion extends ViewRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            UserPredictionChart::class,
            UserPredictionOverTimeChart::class,
        ];
    }
}
