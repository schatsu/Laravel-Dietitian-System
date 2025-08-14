<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TotalClient extends BaseWidget
{
    protected function getStats(): array
    {
        $totalClients = Client::query()->count();

        // Son 7 günün tarihleri
        $days = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));

        // Son 7 günün yeni client sayıları
        $chartData = $days->map(fn($day) => Client::query()->whereDate('created_at', $day)->count())->toArray();

        // Bugün ve dün arasındaki artış
        $todayCount = Client::query()->whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = Client::query()->whereDate('created_at', Carbon::yesterday())->count();

        $increasePercent = $yesterdayCount > 0
            ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100)
            : $todayCount;

        $isPositive = $increasePercent >= 0;

        return [
            Stat::make('Toplam Danışan', $totalClients)
                ->description(($isPositive ? '+' : '') . $increasePercent . '% ' . ($isPositive ? 'artış' : 'azalış'))
                ->descriptionIcon($isPositive ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($chartData)
                ->color($isPositive ? 'success' : 'danger'),
        ];
    }
}
