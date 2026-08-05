<?php

namespace App\Filament\Widgets;

use App\Models\Queue;
use App\Models\QueueCall;
use App\Models\QueueStep;
use App\Models\Station;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use App\Models\QueueStepsTimestamp;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class StaffDashboard extends Widget implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected static ?int $sort = 2;

    protected static string $view = 'filament.widgets.staff-dashboard';
    protected int | string | array $columnSpan = 'full';
    public $station;
    // public $transaction;
    public $status;

    public $assigned_station;
    public $currentQueue;
    public $nextQueues;


    public $active_queues;
    public $done_queues;



    public function mount(){
        // dd(auth()->user()->stations);
        // $this->transaction = auth()->user()->transactions()->first();

        //Station::where('assigned_to', auth()->user()->id)->first();
        $test = auth()->user()->stations;
        $assigned_station = $test->map(function ($station){
            $test1 = $this->getQueuePerStation($station);
            return [
                'active' => isset($test1['active']) ? $test1['active']->count() : 0,
                'id' => $station->id,
                'name' => $station->name,
                'station' => $station,
            ];
        });

        $this->station = auth()->user()->stations()->first();
        $this->assigned_station = $assigned_station;
        $this->refreshQueue();

        $this->status =  $this->station->status;
    }



    public function updateStation($station){
        $station = Station::find($station['id']);
        $this->station = $station;
        $this->status =  $station->status;
        $this->refreshQueue();
    }

    public function getQueuePerStation($station){

        $steps = QueueStep::with('queue')
            ->where('station_id', $station->id)
            // ->where('station_id', 3)
            ->join('queues', 'queues.id', '=', 'queue_steps.queue_id')
            ->whereDate('queues.created_at', Carbon::today())
            ->orderByRaw("
                CASE
                    WHEN queues.external_appointments IS NOT NULL THEN 0
                    WHEN queues.priority_type IS NOT NULL THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('queues.created_at', 'asc')
            ->select('queue_steps.*');

        $active = (clone $steps)->pendingSteps();
        $done = (clone $steps)->completedSteps();
        return[
            'active' => $active,
            'done' => $done,
        ];
    }

    public function refreshQueue(){
        $station = $this->station;
        $queues = $this->getQueuePerStation($station);
        $active_queues = $queues['active'];
        $done = $queues['done'];

        // dd($active_queues->get(),$done->get());

        $first_queue = (clone $active_queues)->first();
        // dd($first_queue);
        $this->active_queues = $active_queues->get();
        $this->done_queues = $done->get();

        if($first_queue){
            $this->getCurrentQueue($first_queue);
        }
        else{
            $this->currentQueue = null;
        }

    }
    public function getCurrentQueue($first_queue)
    {
        $currentQueue = $first_queue->queue;
        if(!$currentQueue){
            $this->currentQueue = null;
            return;
        }
        $this->currentQueue = [
            'id' => $currentQueue->id ?? null,
            'queue_number' => ($currentQueue) ? $currentQueue->getQueueNumber() : null,
            'client_name' => $currentQueue->name ?? null,
            'queue_status' => $currentQueue->status->name ?? null,
            'status_id' => $currentQueue->status_id ?? null,
            'priority_type_id' => $currentQueue->priority->id ?? null ?? null,
            'priority' => $currentQueue->priority->name ?? null,
            'required_documents' => $currentQueue->transaction->required_documents ?? null,
        ];
        return $this->currentQueue;
    }

    public function view_queue($queue_id){
        $station = $this->station;
        $this->dispatch('openQueueModal', $queue_id, $station->id);
    }


    protected function getForms(): array
    {
        return [
            'form1',
        ];
    }
    public function updated($property_name)
    {
        if($property_name == 'status'){
            $this->station->status = $this->{$property_name};
            $this->station->save();

            Notification::make()
                ->title('Station Status Updated')
                ->body('Station status set to ' . ($this->{$property_name} ? 'Online' : 'Offline'))
                ->success()
                ->send();
        }
    }
    public function form1(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('status')
                    ->label('')
                    ->reactive()
                    ->hint('Toggle the station status to go online or offline.')
                    ->default($this->status)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-bolt')
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('staff');
    }
}
