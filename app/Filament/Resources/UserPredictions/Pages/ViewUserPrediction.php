<?php

namespace App\Filament\Resources\UserPredictions\Pages;

use App\Filament\Resources\UserPredictions\UserPredictionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUserPrediction extends ViewRecord
{
    protected static string $resource = UserPredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
