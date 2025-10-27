<?php

namespace App\Filament\Pages;

use App\Enums\DatePeriod;
use App\Filament\Resources\Questions\Widgets\UserPredictionChart;
use App\Filament\Resources\Questions\Widgets\UserPredictionOverTimeChart;
use App\Filament\Widgets\UserPredictionsChart;
use App\Filament\Widgets\UserPredictionsLast7DaysChart;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getWidgets(): array
    {
        return [
            StatsOverview::make(),
            UserPredictionsLast7DaysChart::make(),
        ];
    }

}
