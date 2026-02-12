<?php

namespace App\Filament\Widgets;

use App\Models\Queue;
use App\Models\QueueCall;
use App\Models\Station;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use App\Models\QueueStepsTimestamp;
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


    public function mount(){
        // dd(auth()->user()->stations);
        // $this->transaction = auth()->user()->transactions()->first();

        //Station::where('assigned_to', auth()->user()->id)->first();
        $assigned_station = auth()->user()->stations;
        // dd($assigned_station);
        $this->assigned_station = $assigned_station;

        $this->station = auth()->user()->stations()->first();
        $this->status =  $this->station->status;
        $this->currentQueue = $this->getCurrentQueue();
    }

    public function updateStation($station){
        $station = Station::find($station['id']);
        $this->station = $station;
        $this->status =  $station->status;
        $this->currentQueue = $this->getCurrentQueue();
    }


    public function getCurrentQueue()
    {
        $currentQueue = $this->station->activeQueues()->first();
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
        $this->dispatch('openQueueModal', $queue_id);
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
