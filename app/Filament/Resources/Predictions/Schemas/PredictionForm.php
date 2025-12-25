<?php

namespace App\Filament\Resources\Predictions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;


class PredictionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('category', 'title'),
                Select::make('user_id')
                    ->relationship('user', 'id'),
                Select::make('topic_id')
                    ->relationship('topic', 'title'),

                DateTimePicker::make('closes_at')
                    ->jalali(),
                DateTimePicker::make('starts_at')
                    ->jalali(),
                DateTimePicker::make('resolve_at')
                    ->jalali(),
                Select::make('tags')
                    ->label('Tags')
                    ->multiple()
                    ->relationship('tags', 'title')
                    ->searchable()
                    ->preload(),

                Repeater::make('predictionOptions')
                    ->label('Options')
                    ->relationship()
                    ->schema([
                        TextInput::make('title')
                            ->required(),

                        Toggle::make('is_true')
                            ->label('Correct Answer')
                            ->reactive()
                            ,

                    ])
                    ->rules([
                        fn () => function (string $attribute, $value, \Closure $fail) {
                            $trueCount = collect($value)->where('is_true', true)->count();
                            if ($trueCount > 1) {
                                $fail('Only one option can be marked as correct.');
                            }
                        }
                    ])
                    ->minItems(2)
                    ->required(),
            ])
            ;
    }
}
