<?php

namespace App\Filament\Resources\UserPredictions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserPredictionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('percentage')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                Select::make('question_option_id')
                    ->relationship('questionOption', 'title')
                    ->required(),
            ]);
    }
}
