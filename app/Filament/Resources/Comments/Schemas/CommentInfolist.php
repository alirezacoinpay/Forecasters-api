<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('text')
                    ->columnSpanFull(),
                TextEntry::make('file')
                    ->placeholder('-'),
                TextEntry::make('user.id')
                    ->label('User'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->jalaliDateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->jalaliDateTime()
                    ->placeholder('-'),
            ]);
    }
}
