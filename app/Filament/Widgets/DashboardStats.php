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
    public $completedCount;

    public $total;

    public function mount(){
        if (auth()->user()->hasRole('staff')) {
            $station = auth()->user()->stations()->first();
            $this->activeCount = $station->activeQueues->count();
            $this->pendingCount = $station->pendingQueues->count();
            $this->processingCount = $station->processingQueues->count();
            $this->completedCount = $station->doneQueues->count(); // Count completed queues
            $this->activeCount =$this->activeCount + $this->completedCount;

        } else{
            $stations = Station::all();

            $this->activeCount = $stations->sum(fn ($station) => $station->activeQueues->count());
            $this->pendingCount = $stations->sum(fn ($station) => $station->pendingQueues->count());
            $this->processingCount = $stations->sum(fn ($station) => $station->processingQueues->count());
            $this->completedCount = $stations->sum(fn ($station) => $station->doneQueues->count());
            $this->activeCount = $this->activeCount + $this->completedCount;
        }
        // dd($this->activeCount, $this->pendingCount, $this->processingCount);
        $this->total = $this->activeCount;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('', $this->total)
                ->color('warning')
                ->icon('heroicon-o-users')
                ->description('Total Clients Today'),
            Stat::make('', $this->completedCount)
                ->color('success')
                ->icon('heroicon-o-play')
                ->description('Completed'),
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
