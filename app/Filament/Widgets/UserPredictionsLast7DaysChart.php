<?php

namespace App\Filament\Widgets;

use App\Models\UserPrediction;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class UserPredictionsLast7DaysChart extends ChartWidget
{
    protected ?string $heading = 'User Predictions - Last 7 Days';

    protected function getData(): array
    {
        // Prepare last 7 days
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }

        // Get counts grouped by date
        $data = UserPrediction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::today()->subDays(6)) // last 7 days
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Fill missing dates with 0
        $counts = $dates->map(fn($date) => $data[$date] ?? 0)->toArray();

        // Prepare labels (short format)
        $labels = $dates->map(fn($date) => Carbon::parse($date)->format('D'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'User Predictions',
                    'data' => $counts,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
