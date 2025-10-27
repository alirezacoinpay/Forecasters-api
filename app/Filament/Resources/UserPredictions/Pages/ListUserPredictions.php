<?php

namespace App\Filament\Resources\UserPredictions\Pages;

use App\Filament\Resources\UserPredictions\UserPredictionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserPredictions extends ListRecords
{
    protected static string $resource = UserPredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
