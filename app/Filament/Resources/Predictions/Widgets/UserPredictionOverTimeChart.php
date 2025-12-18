<?php

namespace App\Filament\Resources\Predictions\Widgets;

use App\Enums\DatePeriod;
use App\Models\Prediction;
use App\Models\UserPrediction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class UserPredictionOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'User Predictions Over Time';

    public ?Prediction $record = null;

    public ?string $filter = DatePeriod::TODAY->value;

    protected function getData(): array
    {
        if (! $this->record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $query = UserPrediction::query()
            ->whereHas('predictionOption', function ($q) {
                $q->where('prediction_id', $this->record->id);
            });

        // Apply filter
        $startDate = match($this->filter) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'all' => Carbon::createFromTimestamp(0),
            default => Carbon::now()->subWeek(),
        };

        $predictions = $query
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d'); // group by day
            });

        $labels = $predictions->keys()->toArray();

        $data = $predictions->map(fn($day) => $day->count())->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Predictions',
                    'data' => array_values($data),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->notify('stateUpdated'); // trigger widget refresh
    }
}
