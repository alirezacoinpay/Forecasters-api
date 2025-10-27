<?php

namespace App\Filament\Resources\UserPredictions\Pages;

use App\Filament\Resources\UserPredictions\UserPredictionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserPrediction extends CreateRecord
{
    protected static string $resource = UserPredictionResource::class;
}
