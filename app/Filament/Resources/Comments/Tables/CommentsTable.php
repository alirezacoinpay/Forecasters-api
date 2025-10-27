<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Models\Question;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
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
                TextColumn::make('question.title'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->jalaliDateTime()
                    ->sortable(),
            ])
            ->filters([

                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'mobile')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('question_id')
                    ->label('Question')
                    ->relationship('question', 'title')
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
