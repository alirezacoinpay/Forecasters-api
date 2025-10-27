<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Enums\CategoryStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
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
                    ->formatStateUsing(fn (string $state) => CategoryStatus::find($state))
                    ->badge()
                    ->colors([
                        'success' => fn ($state) => $state === CategoryStatus::ACTIVE->value,
                        'danger' => fn ($state) => $state === CategoryStatus::IN_ACTIVE->value,
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->native()
                    ->options(CategoryStatus::toFormArray())
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
