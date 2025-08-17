<?php

namespace App\Filament\Widgets;

use App\Models\Station;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('', '1')
                ->color('warning')
                ->icon('heroicon-o-users')
                ->description('Total Clients Today'),
            Stat::make('', Carbon::now()->format('i').'min')
                ->color('info')
                ->icon('heroicon-o-clock')
                ->description('Average Wait Time'),
            Stat::make('Manage Stations', Station::where('status', 1)->count())
                ->color('success')
                ->icon('heroicon-o-play')
                ->description('Active Stations')
                ->url('admin/stations'),
            Stat::make('Manage transactions', '1')
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle')
                ->description('Ongoing Transactions')
                ->url('admin/transactions')
        ];
    }

    public static function canView(): bool
    {
        return !auth()->user()?->hasRole('staff');
    }
}
