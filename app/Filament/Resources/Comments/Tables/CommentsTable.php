<?php

namespace App\Filament\Resources\Comments\Tables;


use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text')
                    ->searchable(),
                TextColumn::make('file'),
                TextColumn::make('user.mobile')
                    ->searchable(),
                TextColumn::make('prediction.title'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->jalaliDateTime()
                    ->sortable(),
            ])
            ->filters([

                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'id')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('prediction_id')
                    ->label('Prediction')
                    ->relationship('prediction', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),

            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
