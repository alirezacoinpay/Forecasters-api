<?php

namespace App\Filament\Resources\UserPredictions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Card;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;

class UserPredictionInfolist
{

    public static function configure(Schema $schema): Schema
    {
        $userPredictionPredictionOptionId = $schema->model->id;
        return $schema
            ->components([
                Section::make('Details')
                    ->inlineLabel()
                    ->schema([
                        TextEntry::make('percentage'),
                        TextEntry::make('user.mobile')
                            ->label('User'),

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('Prediction')
                    ->schema([

                        TextEntry::make('prediction.title')
                        ->hiddenLabel(),
                        RepeatableEntry::make('prediction.predictionOptions')
                            ->hiddenLabel()
                            ->table([
                                RepeatableEntry\TableColumn::make('title'),
                            ])
                            ->schema([
                                TextEntry::make('title')
                                    ->color(function ($record) use ($userPredictionPredictionOptionId) {
                                        return $record->id === $userPredictionPredictionOptionId
                                            ? 'warning'
                                            : 'secondary';
                                    })
                                    ->hiddenLabel(),
                            ]),
                    ]),

            ]);
    }
}
