<?php

namespace App\Filament\Resources\Predictions\Pages;

use App\Filament\Resources\Predictions\PredictionResource;
use App\Filament\Resources\Predictions\Widgets\UserPredictionChart;
use App\Filament\Resources\Predictions\Widgets\UserPredictionOverTimeChart;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrediction extends ViewRecord
{
    protected static string $resource = PredictionResource::class;

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
