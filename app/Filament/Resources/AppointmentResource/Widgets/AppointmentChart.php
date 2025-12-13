<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Zap\Enums\ScheduleTypes;
use Zap\Models\Schedule;

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
        $dietitian = User::role('super_admin')->first();

        foreach (range(1, 12) as $month) {
            // Onaylanan randevular
            $approved[] = Schedule::query()
                ->where('schedulable_type', User::class)
                ->where('schedulable_id', $dietitian?->id)
                ->where('schedule_type', ScheduleTypes::APPOINTMENT)
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->whereJsonContains('metadata->status', 'approved')
                ->count();

            // Bekleyen randevular
            $pending[] = Schedule::query()
                ->where('schedulable_type', User::class)
                ->where('schedulable_id', $dietitian?->id)
                ->where('schedule_type', ScheduleTypes::APPOINTMENT)
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->where(function ($query) {
                    $query->whereJsonContains('metadata->status', 'pending')
                        ->orWhereNull('metadata->status');
                })
                ->count();

            // Reddedilen randevular
            $rejected[] = Schedule::query()
                ->where('schedulable_type', User::class)
                ->where('schedulable_id', $dietitian?->id)
                ->where('schedule_type', ScheduleTypes::APPOINTMENT)
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->whereJsonContains('metadata->status', 'rejected')
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
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => 'rgba(34,197,94,0.9)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Beklemede',
                    'data' => $pending,
                    'backgroundColor' => 'rgba(250,204,21,0.7)',
                    'borderColor' => 'rgba(250,204,21,1)',
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => 'rgba(250,204,21,0.9)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Reddedilen',
                    'data' => $rejected,
                    'backgroundColor' => 'rgba(239,68,68,0.7)',
                    'borderColor' => 'rgba(239,68,68,1)',
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => 'rgba(239,68,68,0.9)',
                    'tension' => 0.3,
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
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ];
    }
}
