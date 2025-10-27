<?php

namespace App\Filament\Resources\UserPredictions\Pages;

use App\Filament\Resources\UserPredictions\UserPredictionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUserPrediction extends EditRecord
{
    protected static string $resource = UserPredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
