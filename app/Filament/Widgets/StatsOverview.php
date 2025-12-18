<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Prediction;
use App\Models\Comment;
use App\Models\UserPrediction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class StatsOverview extends BaseWidget
{
    use HasFiltersSchema;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count())
                ->description("Users total ")
                ->icon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Predictions', Prediction::count())
                ->description("Predictions total ")
                ->icon('heroicon-o-chart-bar')
                ->color('info'),

            Stat::make('Comments', Comment::count())
                ->description("Comments total ")
                ->icon('heroicon-o-chat-bubble-left')
                ->color('warning'),

            Stat::make('Predictions', UserPrediction::count())
                ->description("Predictions total ")
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),
        ];
    }
}
