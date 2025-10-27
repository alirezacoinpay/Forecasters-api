<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mobile')
                    ->required(),
                TextInput::make('username'),
                DateTimePicker::make('mobile_verified_at'),
                TextInput::make('password')
                    ->password(),
            ]);
    }
}
