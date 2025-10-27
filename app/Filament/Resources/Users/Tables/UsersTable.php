<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mobile')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),

                TextColumn::make('mobile_verified_at')
                    ->label('Mobile Verified')
                    ->getStateUsing(fn ($record) => $record->mobile_verified_at ? 'Verified' : 'Not Verified')
                    ->badge()
                    ->color(fn ($record) => $record->mobile_verified_at ? 'success' : 'danger'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->jalaliDateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('verification')
                    ->label('Mobile Verified')
                    ->options([
                        'verified' => 'Verified',
                        'unverified' => 'Not Verified',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;

                        return match ($value) {
                            'verified' => $query->whereNotNull('mobile_verified_at'),
                            'unverified' => $query->whereNull('mobile_verified_at'),
                            default => $query,
                        };
                    })
                    ->native(false),

                Filter::make('created_at')
                    ->label('Created Between')
                    ->form([
                        DatePicker::make('from')->label('From')->jalali(),
                        DatePicker::make('until')->label('Until')->jalali(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
