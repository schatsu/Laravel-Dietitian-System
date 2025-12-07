<?php

namespace App\Filament\Resources\ClientPaymentResource\Widgets;

use App\Enums\ClientPaymentStatusEnum;
use App\Models\ClientPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ClientPaymentStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $startOfWeek = $now->clone()->startOfWeek();
        $endOfWeek = $now->clone()->endOfWeek();
        $startOfLastWeek = $now->clone()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->clone()->subWeek()->endOfWeek();


        $currentMonthName = $now->translatedFormat('F');
        $previousMonthName = $now->copy()->subMonth()->translatedFormat('F');

        $baseQuery = ClientPayment::query()
            ->where('payment_status', ClientPaymentStatusEnum::COMPLETED);


        $dailyEarnings = (clone $baseQuery)->whereDate('payment_date', $today)->sum('amount');
        $yesterdayEarnings = (clone $baseQuery)->whereDate('payment_date', $yesterday)->sum('amount');


        $dailyChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $val = (clone $baseQuery)->whereDate('payment_date', $date)->sum('amount');
            $dailyChart[] = $val;
        }

        $dailyChange = 0;
        if ($yesterdayEarnings > 0) {
            $dailyChange = (($dailyEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100;
        } elseif ($dailyEarnings > 0) {
            $dailyChange = 100;
        }

        if ($dailyChange > 0) {
            $dailyColor = 'success'; $dailyIcon = 'heroicon-m-arrow-trending-up';
        } elseif ($dailyChange < 0) {
            $dailyColor = 'danger'; $dailyIcon = 'heroicon-m-arrow-trending-down';
        } else {
            $dailyColor = 'warning'; $dailyIcon = 'heroicon-m-minus';
        }

        $weeklyEarnings = (clone $baseQuery)
            ->whereBetween('payment_date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        $lastWeekEarnings = (clone $baseQuery)
            ->whereBetween('payment_date', [$startOfLastWeek, $endOfLastWeek])
            ->sum('amount');


        $weeklyChart = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $val = (clone $baseQuery)->whereDate('payment_date', $date)->sum('amount');
            $weeklyChart[] = $val;
        }

        $weeklyChange = 0;
        if ($lastWeekEarnings > 0) {
            $weeklyChange = (($weeklyEarnings - $lastWeekEarnings) / $lastWeekEarnings) * 100;
        } elseif ($weeklyEarnings > 0) {
            $weeklyChange = 100;
        }

        if ($weeklyChange > 0) {
            $weeklyColor = 'success'; $weeklyIcon = 'heroicon-m-arrow-trending-up';
        } elseif ($weeklyChange < 0) {
            $weeklyColor = 'danger'; $weeklyIcon = 'heroicon-m-arrow-trending-down';
        } else {
            $weeklyColor = 'warning'; $weeklyIcon = 'heroicon-m-minus';
        }

        if ($startOfWeek->month === $endOfWeek->month) {
            $weekLabel = $startOfWeek->translatedFormat('j') . ' - ' . $endOfWeek->translatedFormat('j F');
        } else {
            $weekLabel = $startOfWeek->translatedFormat('j F') . ' - ' . $endOfWeek->translatedFormat('j F');
        }

        $monthlyEarnings = (clone $baseQuery)
            ->whereMonth('payment_date', $now->month)
            ->whereYear('payment_date', $now->year)
            ->sum('amount');

        $previousMonthEarnings = (clone $baseQuery)
            ->whereMonth('payment_date', $now->copy()->subMonth()->month)
            ->whereYear('payment_date', $now->copy()->subMonth()->year)
            ->sum('amount');

        $monthlyChart = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $val = (clone $baseQuery)
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            $monthlyChart[] = round($val, 2);
        }

        $monthlyChange = 0;
        if ($previousMonthEarnings > 0) {
            $monthlyChange = (($monthlyEarnings - $previousMonthEarnings) / $previousMonthEarnings) * 100;
        } elseif ($monthlyEarnings > 0) {
            $monthlyChange = 100;
        }

        if ($monthlyChange > 0) {
            $monthlyColor = 'success'; $monthlyIcon = 'heroicon-m-arrow-trending-up';
        } elseif ($monthlyChange < 0) {
            $monthlyColor = 'danger'; $monthlyIcon = 'heroicon-m-arrow-trending-down';
        } else {
            $monthlyColor = 'warning'; $monthlyIcon = 'heroicon-m-minus';
        }


        $totalEarnings = (clone $baseQuery)->sum('amount');

        return [
            Stat::make('Günlük Kazanç', number_format($dailyEarnings, 2, ',', '.') . ' ₺')
                ->description(
                    $dailyChange == 0
                        ? 'Düne göre değişim yok'
                        : 'Düne göre %' . number_format(abs($dailyChange), 1, ',', '.') . ($dailyChange > 0 ? ' artış' : ' azalış')
                )
                ->descriptionIcon($dailyIcon)
                ->chart($dailyChart)
                ->color($dailyColor),

            Stat::make('Haftalık Kazanç', number_format($weeklyEarnings, 2, ',', '.') . ' ₺')
                ->description(
                    $weekLabel . ' · ' .
                    ($weeklyChange == 0
                        ? 'Önceki haftaya göre değişim yok'
                        : 'Önceki haftaya göre %' . number_format(abs($weeklyChange), 1, ',', '.') . ($weeklyChange > 0 ? ' artış' : ' azalış')
                    )
                )
                ->descriptionIcon($weeklyIcon)
                ->chart($weeklyChart)
                ->color($weeklyColor),

            Stat::make($currentMonthName . ' Kazancı', number_format($monthlyEarnings, 2, ',', '.') . ' ₺')
                ->description(
                    $monthlyChange == 0
                        ? $previousMonthName . ' ayına göre değişim yok'
                        : $previousMonthName . ' ayına göre %' . number_format(abs($monthlyChange), 1, ',', '.') . ($monthlyChange > 0 ? ' artış' : ' azalış')
                )
                ->descriptionIcon($monthlyIcon)
                ->chart($monthlyChart)
                ->color($monthlyColor),

            Stat::make('Toplam Kazanç', number_format($totalEarnings, 2, ',', '.') . ' ₺')
                ->description('Tüm zamanların geliri')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
