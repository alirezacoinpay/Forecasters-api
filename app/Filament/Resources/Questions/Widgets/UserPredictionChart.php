<?php

namespace App\Filament\Resources\Questions\Widgets;

use App\Models\Question;
use Filament\Widgets\ChartWidget;

class UserPredictionChart extends ChartWidget
{
    protected ?string $heading = 'User Prediction Chart';

    public ?Question $record = null;

    protected function getData(): array
    {
        if (! $this->record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $options = $this->record->questionOptions;

        $labels = $options->pluck('title')->toArray();

        $data = $options->map(function ($option) {
             return $option->userPredictions->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'User Predictions',
                    'data' => $data,
                    'backgroundColor' => [
                        '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#F43F5E', '#22D3EE', '#A3E635', '#FACC15'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
