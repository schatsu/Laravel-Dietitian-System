<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class AppointmentChart extends ChartWidget
{
    protected static ?string $heading = 'Randevular';


    protected function getData(): array
    {
        $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];

        $approved = [];
        $pending = [];
        $rejected = [];

        foreach (range(1, 12) as $month) {
            $approved[] = Appointment::query()
                ->whereHas('slot', function ($q) use ($month) {
                    $q->whereMonth('date', $month);
                })->where('status', AppointmentStatusEnum::APPROVED)
                ->count();

            $pending[] = Appointment::query()
                ->whereHas('slot', function ($q) use ($month) {
                    $q->whereMonth('date', $month);
                })->where('status', AppointmentStatusEnum::PENDING)
                ->count();

            $rejected[] = Appointment::query()
                ->whereHas('slot', function ($q) use ($month) {
                    $q->whereMonth('date', $month);
                })->where('status', AppointmentStatusEnum::REJECTED)
                ->count();
        }


        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Onaylanan',
                    'data' => $approved,
                    'backgroundColor' => 'rgba(34,197,94,0.7)',
                    'borderColor' => 'rgba(34,197,94,1)',
                    'borderWidth' => 1,
                    'hoverBackgroundColor' => 'rgba(34,197,94,0.9)',
                ],
                [
                    'label' => 'Beklemede',
                    'data' => $pending,
                    'backgroundColor' => 'rgba(250,204,21,0.7)',
                    'borderColor' => 'rgba(250,204,21,1)',
                    'borderWidth' => 1,
                    'hoverBackgroundColor' => 'rgba(250,204,21,0.9)',
                ],
                [
                    'label' => 'Reddedilen',
                    'data' => $rejected,
                    'backgroundColor' => 'rgba(239,68,68,0.7)',
                    'borderColor' => 'rgba(239,68,68,1)',
                    'borderWidth' => 1,
                    'hoverBackgroundColor' => 'rgba(239,68,68,0.9)',
                ],
            ],
        ];

    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
