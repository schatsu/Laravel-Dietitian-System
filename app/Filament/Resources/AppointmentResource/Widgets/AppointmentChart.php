<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class AppointmentChart extends ChartWidget
{
    protected static ?string $heading = 'Aylık Randevu Sayısı';


    public ?string $filter = 'this_year';

    protected function getFilters(): ?array
    {
        $years = range(now()->year, now()->year - 2);
        $filters = ['this_year' => 'Bu Yıl'];

        foreach ($years as $year) {
            $filters[(string) $year] = $year;
        }

        return $filters;
    }

    protected function getData(): array
    {
        $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];

        $approved = [];
        $pending = [];
        $rejected = [];

        $year = $this->filter === 'this_year' ? now()->year : (int) $this->filter;

        foreach (range(1, 12) as $month) {
            $approved[] = Appointment::query()
                ->whereHas('slot', function ($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                        ->whereYear('date', $year);
                })
                ->where('status', AppointmentStatusEnum::APPROVED)
                ->count();

            $pending[] = Appointment::query()
                ->whereHas('slot', function ($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                        ->whereYear('date', $year);
                })
                ->where('status', AppointmentStatusEnum::PENDING)
                ->count();

            $rejected[] = Appointment::query()
                ->whereHas('slot', function ($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                        ->whereYear('date', $year);
                })
                ->where('status', AppointmentStatusEnum::REJECTED)
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
        return 'line';
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
