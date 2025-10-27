<?php

namespace App\Filament\Resources\Topics\Tables;

use App\Enums\CategoryStatus;
use App\Enums\TopicStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                IconColumn::make('icon')
                    ->icon(fn (string $state): string => $state)
                    ->label('Icon'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state) => TopicStatus::find($state))
                    ->badge()
                    ->colors([
                        'success' => fn ($state) => $state === TopicStatus::ACTIVE->value,
                        'danger' => fn ($state) => $state === TopicStatus::IN_ACTIVE->value,
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->native()
                    ->options(TopicStatus::toFormArray())
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
