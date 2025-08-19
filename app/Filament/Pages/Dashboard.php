<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AppointmentResource\Widgets\AppointmentChart;
use App\Filament\Resources\AppointmentResource\Widgets\AppointmentOverview;
use App\Filament\Resources\AppointmentResource\Widgets\LastAppointments;
use App\Filament\Resources\ClientPaymentResource\Widgets\ClientPaymentsChart;
use App\Filament\Resources\ClientResource\Widgets\TotalClient;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget;

class Dashboard extends \Filament\Pages\Dashboard
{

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            LastAppointments::class,
            ClientPaymentsChart::class,
            AppointmentChart::class,
        ];
    }


    public function getColumns(): int | string | array
    {
        return 2;
    }
}
