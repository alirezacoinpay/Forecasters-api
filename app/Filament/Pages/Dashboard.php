<?php

namespace App\Filament\Pages;


use App\Filament\Widgets\UserPredictionsLast7DaysChart;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

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
