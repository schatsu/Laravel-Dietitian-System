<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\Blog;
use App\Models\Client;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    /**
     * Build all stats
     *
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        return [
            $this->getClientsStat(),
            $this->getAppointmentsStat(),
            $this->getServicesStat(),
            $this->getBlogsStat(),
            $this->getApprovedAppointmentsStat(),
            $this->getRejectedAppointmentsStat(),
        ];
    }

    protected function getAppointmentsStat(): Stat
    {
        $totalAppointments = Appointment::query()->count();

        $days = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));
        $chartData = $days
            ->map(fn($day) => Appointment::query()->whereDate('created_at', $day)->count())
            ->toArray();

        $todayCount = Appointment::query()->whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = Appointment::query()->whereDate('created_at', Carbon::yesterday())->count();

        $increasePercent = $yesterdayCount > 0
            ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100)
            : $todayCount;

        $color = 'warning';
        $icon = 'heroicon-m-minus';
        $description = '0% değişim';

        if ($increasePercent > 0) {
            $color = 'success';
            $icon = 'heroicon-m-arrow-trending-up';
            $description = '+' . $increasePercent . '% artış';
        } elseif ($increasePercent < 0) {
            $color = 'danger';
            $icon = 'heroicon-m-arrow-trending-down';
            $description = $increasePercent . '% azalış';
        }

        return Stat::make('Toplam Randevu', $totalAppointments)
            ->description($description)
            ->descriptionIcon($icon)
            ->chart($chartData)
            ->color($color);
    }

    protected function getClientsStat(): Stat
    {
        $totalClients = Client::query()->count();

        $days = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));
        $chartData = $days
            ->map(fn($day) => Client::query()->whereDate('created_at', $day)->count())
            ->toArray();

        $todayCount = Client::query()->whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = Client::query()->whereDate('created_at', Carbon::yesterday())->count();

        $increasePercent = $yesterdayCount > 0
            ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100)
            : $todayCount;

        $color = 'warning';
        $icon = 'heroicon-m-minus';
        $description = '0% değişim';

        if ($increasePercent > 0) {
            $color = 'success';
            $icon = 'heroicon-m-arrow-trending-up';
            $description = '+' . $increasePercent . '% artış';
        } elseif ($increasePercent < 0) {
            $color = 'danger';
            $icon = 'heroicon-m-arrow-trending-down';
            $description = $increasePercent . '% azalış';
        }

        return Stat::make('Toplam Danışan', $totalClients)
            ->description($description)
            ->descriptionIcon($icon)
            ->chart($chartData)
            ->color($color);
    }

    protected function getBlogsStat(): Stat
    {
        $totalBlogs = Blog::query()->count();
        return Stat::make('Toplam Blog', $totalBlogs);
    }

    public static function getServicesStat(): Stat
    {
        $totalService = Service::query()->count();
        return Stat::make('Toplam Hizmet', $totalService);
    }

    public static function getApprovedAppointmentsStat(): Stat
    {
        $approvedAppointments = Appointment::query()
            ->where('status', AppointmentStatusEnum::APPROVED)
            ->count();
        return Stat::make('Onaylanan Randevu', $approvedAppointments);
    }

    public static function getRejectedAppointmentsStat(): Stat
    {
        $rejectedAppointments = Appointment::query()
            ->where('status', AppointmentStatusEnum::REJECTED)
            ->count();
        return Stat::make('Reddedilen Randevu', $rejectedAppointments);
    }

}
