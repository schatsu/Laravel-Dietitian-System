<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\Blog;
use App\Models\Client;
use App\Models\Service;
use Closure;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30';

    protected function getStats(): array
    {
        return [

            $this->buildTrendStat(Client::class, 'Toplam Danışan'),


            $this->buildTrendStat(Appointment::class, 'Toplam Randevu'),


            $this->buildTrendStat(
                Appointment::class,
                'Onaylanan Randevu',
                fn (Builder $query) => $query->where('status', AppointmentStatusEnum::APPROVED)
            ),


            $this->buildTrendStat(
                Appointment::class,
                'Reddedilen Randevu',
                fn (Builder $query) => $query->where('status', AppointmentStatusEnum::REJECTED)
            ),


            $this->buildTrendStat(Service::class, 'Toplam Hizmet'),


            $this->buildTrendStat(Blog::class, 'Toplam Blog'),
        ];
    }

    /**
     * *
     * @param string $model
     * @param string $label
     * @param Closure|null $modifyQuery
     * @return Stat
     */
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
