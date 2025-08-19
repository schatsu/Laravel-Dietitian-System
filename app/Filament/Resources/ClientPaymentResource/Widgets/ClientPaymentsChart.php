<?php

namespace App\Filament\Resources\ClientPaymentResource\Widgets;

use App\Enums\ClientPaymentStatusEnum;
use App\Models\ClientPayment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ClientPaymentsChart extends ChartWidget
{

    protected static ?string $heading = 'Danışan Ödemeleri';
    protected static string $color = 'success';

    public ?string $filter = 'daily';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Günlük',
            'weekly' => 'Haftalık',
            'monthly' => 'Aylık',
        ];
    }

    protected function getData(): array
    {
        return match ($this->filter) {
            'weekly' => $this->getWeeklyData(),
            'monthly' => $this->getMonthlyData(),
            default => $this->getDailyData(),
        };
    }

    protected function getDailyData(): array
    {
        $start = now()->subDays(6)->startOfDay();

        $rows = ClientPayment::query()
            ->where('payment_status', ClientPaymentStatusEnum::COMPLETED)
            ->where('payment_date', '>=', $start)
            ->selectRaw("to_char(payment_date, 'YYYY-MM-DD') as day, SUM(amount) as total")
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];

        foreach (range(0, 6) as $i) {
            $date = $start->copy()->addDays($i);
            $key = $date->format('Y-m-d'); // matches to_char format
            $labels[] = $date->translatedFormat('d M');
            $data[] = (float) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Toplam Ödeme (₺)',
                    'data' => $data,
                    'backgroundColor' => '#16a34a',
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getWeeklyData(): array
    {
        $start = now()->subWeeks(7)->startOfWeek();

        $rows = ClientPayment::query()
            ->where('payment_status', ClientPaymentStatusEnum::COMPLETED)
            ->where('payment_date', '>=', $start)
            ->selectRaw("to_char(DATE_TRUNC('week', payment_date), 'YYYY-MM-DD') as week_start, SUM(amount) as total")
            ->groupBy('week_start')
            ->orderBy('week_start')
            ->pluck('total', 'week_start');

        $labels = [];
        $data = [];

        foreach (range(0, 7) as $i) {
            $week = $start->copy()->addWeeks($i)->startOfWeek();
            $key = $week->format('Y-m-d'); // matches to_char
            $labels[] = $week->translatedFormat('d M') . ' - ' . $week->copy()->endOfWeek()->translatedFormat('d M');
            $data[] = (float) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Haftalık Ödeme (₺)',
                    'data' => $data,
                    'backgroundColor' => '#16a34a',
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getMonthlyData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $rows = ClientPayment::query()
            ->where('payment_status', ClientPaymentStatusEnum::COMPLETED)
            ->where('payment_date', '>=', $start)
            ->selectRaw("to_char(DATE_TRUNC('month', payment_date), 'YYYY-MM') as month_start, SUM(amount) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->pluck('total', 'month_start');

        $labels = [];
        $data = [];

        foreach (range(0, 11) as $i) {
            $month = $start->copy()->addMonths($i)->startOfMonth();
            $key = $month->format('Y-m'); // matches to_char
            $labels[] = $month->translatedFormat('M Y');
            $data[] = (float) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Aylık Ödeme (₺)',
                    'data' => $data,
                    'backgroundColor' => '#16a34a',
                ],
            ],
            'labels' => $labels,
        ];
    }

}
