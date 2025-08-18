<?php

namespace App\Filament\Widgets;

use App\Models\Station;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;
    public $station;
    public $activeCount;
    public $pendingCount;
    public $processingCount;
    public $total;

    public function mount(){
        if (auth()->user()->hasRole('staff')) {
            $station = auth()->user()->stations()->first();

            $this->activeCount = $station->activeQueues->count(); // Count active queues
            $this->pendingCount = $station->pendingQueues->count(); // Count pending queues
            $this->processingCount = $station->processingQueues->count(); // Count processing queues
        } else{
            $stations = Station::all();

            $this->activeCount = $stations->sum(fn ($station) => $station->queues()->active()->count());
            $this->pendingCount = $stations->sum(fn ($station) => $station->queues()->active()->where('status_id', 1)->count());
            $this->processingCount = $stations->sum(fn ($station) => $station->queues()->active()->where('status_id', 2)->count());
        }
        $this->total = $this->activeCount + $this->pendingCount + $this->processingCount;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('', $this->total)
                ->color('warning')
                ->icon('heroicon-o-users')
                ->description('Total Clients Today'),
            Stat::make('', $this->activeCount)
                ->color('success')
                ->icon('heroicon-o-play')
                ->description('Active Queues'),
            Stat::make('', $this->processingCount)
                ->color('info')
                ->icon('heroicon-o-clock')
                ->description('Processing'),
            Stat::make('', $this->pendingCount)
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle')
                ->description('Pending')
        ];
    }
}
