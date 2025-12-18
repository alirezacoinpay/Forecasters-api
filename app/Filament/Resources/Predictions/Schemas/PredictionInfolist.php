<?php
namespace App\Filament\Resources\Predictions\Schemas;

use App\Filament\Resources\Predictions\Widgets\UserPredictionChart;
use App\Models\Prediction;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;

class PredictionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Prediction Details')
                    ->inlineLabel()
                    ->columnSpanFull()
                    ->schema([

                        TextEntry::make('title'),
                        TextEntry::make('text')
                            ->columnSpanFull(),
                        TextEntry::make('category.title')
                            ->label('Category')
                            ->placeholder('-'),
                        TextEntry::make('user.mobile')
                            ->label('User')
                            ->placeholder('-'),
                        TextEntry::make('topic.title')
                            ->label('Topic')
                            ->placeholder('-'),
                        TextEntry::make('closes_at')
                            ->dateTime()
                            ->jalaliDateTime()
                            ->placeholder('-'),
                        TextEntry::make('starts_at')
                            ->dateTime()
                            ->jalaliDateTime()
                            ->placeholder('-'),
                        TextEntry::make('resolve_at')
                            ->dateTime()
                            ->jalaliDateTime()
                            ->placeholder('-'),
                    ])
                ,

                RepeatableEntry::make('predictionOptions')
                    ->label('Prediction Options')
                    ->table([
                        TableColumn::make('Title'),
                        TableColumn::make('Resolvation')
                        ->width("10%"),
                    ])
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('title')
                            ->formatStateUsing(function ($record) {
                                return $record->is_true ? '✅' : '❌';
                            })
                    ])
                    ->columnSpanFull(),
        ]);
    }
}
