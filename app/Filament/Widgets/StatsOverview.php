<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Closure;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Zap\Enums\ScheduleTypes;
use Zap\Models\Schedule;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            $this->buildAppointmentStat('Toplam Randevu'),
            $this->buildAppointmentStat('Onay Bekleyen Randevu', 'pending'),

            $this->buildAppointmentStat('Onaylanan Randevu', 'approved'),

            $this->buildAppointmentStat('Reddedilen Randevu', 'rejected'),

            $this->buildTrendStat(Client::class, 'Toplam Danışan'),

            $this->buildTrendStat(Blog::class, 'Toplam Blog'),
        ];
    }


    protected function buildAppointmentStat(string $label, ?string $status = null): Stat
    {
        $dietitian = User::role('super_admin')->first();

        $baseQuery = fn() => Schedule::query()
            ->where('schedulable_type', User::class)
            ->where('schedulable_id', $dietitian?->id)
            ->where('schedule_type', ScheduleTypes::APPOINTMENT);

        $query = $baseQuery();
        if ($status) {
            $query->whereJsonContains('metadata->status', $status);
        }

        $totalCount = (clone $query)->count();

        $chartData = collect(range(6, 0))->map(function ($i) use ($baseQuery, $status) {
            $dayQuery = $baseQuery()->whereDate('start_date', Carbon::today()->subDays($i));
            if ($status) {
                $dayQuery->whereJsonContains('metadata->status', $status);
            }
            return $dayQuery->count();
        })->toArray();

        $todayQuery = $baseQuery()->whereDate('start_date', Carbon::today());
        $yesterdayQuery = $baseQuery()->whereDate('start_date', Carbon::yesterday());

        if ($status) {
            $todayQuery->whereJsonContains('metadata->status', $status);
            $yesterdayQuery->whereJsonContains('metadata->status', $status);
        }

        $todayCount = $todayQuery->count();
        $yesterdayCount = $yesterdayQuery->count();

        $increasePercent = 0;
        if ($yesterdayCount > 0) {
            $increasePercent = (($todayCount - $yesterdayCount) / $yesterdayCount) * 100;
        } elseif ($todayCount > 0) {
            $increasePercent = 100;
        }

        $color = 'warning';
        $icon = 'heroicon-m-minus';
        $description = 'Düne göre değişim yok';

        if ($increasePercent > 0) {
            $color = 'success';
            $icon = 'heroicon-m-arrow-trending-up';
            $description = 'Düne göre %' . round($increasePercent) . ' artış';
        } elseif ($increasePercent < 0) {
            $color = 'danger';
            $icon = 'heroicon-m-arrow-trending-down';
            $description = 'Düne göre %' . abs(round($increasePercent)) . ' azalış';
        }

        return Stat::make($label, $totalCount)
            ->description($description)
            ->descriptionIcon($icon)
            ->chart($chartData)
            ->color($color);
    }

    protected function buildTrendStat(string $model, string $label, ?Closure $modifyQuery = null): Stat
    {
        $query = $model::query();

        if ($modifyQuery) {
            $modifyQuery($query);
        }

        $totalCount = (clone $query)->count();

        $chartData = collect(range(6, 0))->map(function ($i) use ($query) {
            return (clone $query)
                ->whereDate('created_at', Carbon::today()->subDays($i))
                ->count();
        })->toArray();

        $todayCount = (clone $query)->whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = (clone $query)->whereDate('created_at', Carbon::yesterday())->count();

        $increasePercent = 0;
        if ($yesterdayCount > 0) {
            $increasePercent = (($todayCount - $yesterdayCount) / $yesterdayCount) * 100;
        } elseif ($todayCount > 0) {
            $increasePercent = 100;
        }

        $color = 'warning';
        $icon = 'heroicon-m-minus';
        $description = 'Düne göre değişim yok';

        if ($increasePercent > 0) {
            $color = 'success';
            $icon = 'heroicon-m-arrow-trending-up';
            $description = 'Düne göre %' . round($increasePercent) . ' artış';
        } elseif ($increasePercent < 0) {
            $color = 'danger';
            $icon = 'heroicon-m-arrow-trending-down';
            $description = 'Düne göre %' . abs(round($increasePercent)) . ' azalış';
        }

        return Stat::make($label, $totalCount)
            ->description($description)
            ->descriptionIcon($icon)
            ->chart($chartData)
            ->color($color);
    }
}
