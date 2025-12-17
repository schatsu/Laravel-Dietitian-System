<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AppointmentResource\Widgets\AppointmentChart;
use App\Filament\Resources\AppointmentResource\Widgets\AppointmentOverview;
use App\Filament\Resources\AppointmentResource\Widgets\LastAppointments;
use App\Filament\Resources\ClientPaymentResource\Widgets\ClientPaymentsChart;
use App\Filament\Resources\ClientPaymentResource\Widgets\ClientPaymentStats;
use App\Filament\Resources\ClientResource\Widgets\TotalClient;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UpcomingAppointments;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            ClientPaymentStats::class,
            StatsOverview::class,
            LastAppointments::class,
            ClientPaymentsChart::class,
            AppointmentChart::class,
        ];
    }


    public function getColumns(): int|string|array
    {
        return 2;
    }

    public static function getSort(): int
    {
        return (int) (static::$navigationSort ?? 0);
    }
}
