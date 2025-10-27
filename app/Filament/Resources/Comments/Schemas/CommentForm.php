<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('file'),
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
            ]);
    }
}
