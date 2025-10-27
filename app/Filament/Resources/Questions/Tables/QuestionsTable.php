<?php

namespace App\Filament\Resources\Questions\Tables;

use App\Helpers\DateHelper;
use App\Models\Question;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('category.title')
                    ->searchable(),
                TextColumn::make('topic.title')
                    ->searchable(),
                TextColumn::make('user.mobile')
                    ->searchable(),

                TextColumn::make('tags.title')
                    ->label('Tags')
                    ->badge()
                    ->separator(', ')
                    ->colors([
                        'primary',
                    ]),
                TextColumn::make('starts_at')
                    ->jalaliDateTime()
                    ->label('Starts / Closes')
                    ->description(fn (Question $record): string => DateHelper::toJalali($record->closes_at)),
                TextColumn::make('resolve_at')
                    ->jalaliDate()
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([

                SelectFilter::make('status')
                    ->label('Status')
                    ->multiple() // ✅ allow multi-select
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                        'resolved' => 'Resolved',
                        'not_resolved' => 'Not Resolved',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $values = $data['values'] ?? []; // because multiple() returns an array
                        $now = Carbon::now();

                        // apply each status as OR condition (any match)
                        $query->where(function ($query) use ($values, $now) {
                            foreach ($values as $value) {
                                $query->where(function ($subQuery) use ($value, $now) {
                                    match ($value) {
                                        'open' => $subQuery
                                            ->where('starts_at', '<=', $now)
                                            ->where('closes_at', '>', $now)
                                            ->whereNull('resolve_at'),

                                        'closed' => $subQuery
                                            ->where('closes_at', '<=', $now)
                                            ->whereNull('resolve_at'),

                                        'resolved' => $subQuery->whereNotNull('resolve_at'),

                                        'not_resolved' => $subQuery->whereNull('resolve_at'),

                                        default => null,
                                    };
                                });
                            }
                        });

                        return $query;
                    })
                    ->native(false),

                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('topic_id')
                    ->label('Topic')
                    ->relationship('topic', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),

                // 🟠 Tag Filter (multi-select since a question can have multiple tags)
                SelectFilter::make('tags')
                    ->label('Tags')
                    ->multiple()
                    ->relationship('tags', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),
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
