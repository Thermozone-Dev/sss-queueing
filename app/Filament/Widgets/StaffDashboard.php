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

class StaffDashboard extends Widget implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = null;
    protected static string $view = 'filament.widgets.staff-dashboard';
    protected int | string | array $columnSpan = 'full';
    public $station;
    public $transaction;
    public $status;


    public $currentQueue;
    public $nextQueues;


    public function mount(){

        $this->transaction = auth()->user()->transactions()->first();

        //Station::where('assigned_to', auth()->user()->id)->first();
        $this->station = Station::find(1);
        $this->status =  $this->station->status;
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

    public function call_queue($queue_id){
        if(!QueueStepsTimestamp::where('queue_id', $queue_id)->first()){
            QueueStepsTimestamp::create(
                [
                    'queue_id' => $queue_id,
                    'first_called_at' => now(),
                ]);
        };
        QueueCall::updateOrCreate(
            ['queue_id' => $queue_id],
            ['is_shown' => false, 'should_remove' => false]
        );
        return;
    }

    public function recall_queue($queue_id){

        $this->updateTimeStamps($queue_id, 'recalled_last_at');
        QueueCall::updateOrCreate(
            ['queue_id' => $queue_id],
            ['is_shown' => false, 'should_remove' => false]
        );
        return;
    }

    public function update_queue($queue_id, $status){
        $column = null;
        $shouldRemove = false;
        $queue = Queue::find($queue_id);
        if($status == 2){
            $column = 'processed_at';
            $body = 'Queue status set to processing.';
            $color = 'success';
            $shouldRemove = true;
        }

        if($status == 5){
            $column = 'removed_at';
            $body = 'Queue status set to removed.';
            $color = 'danger';
            $shouldRemove = true;
        }

        if($status == 4){
            $column = 'completed_at';
            $body = 'Queue status set to completed.';
            $color = 'success';
            $shouldRemove = true;
        }

        $queue_call = QueueCall::where('queue_id', $queue_id)->first();
        if($queue_call){
            $queue_call->should_remove = $shouldRemove;
            $queue_call->update();
        }
        $this->updateTimeStamps($queue_id, $column);
        $queue->status_id = $status;
        $queue->save();

        if(!$this->station->pendingQueues()->first()){
            $body = 'There are no more queues to process.';
            $title = 'Queue Empty';
        }

        Notification::make()
            ->title($title ?? 'Queue Updated')
            ->body($body)
            ->success()
            ->color($color)
            ->send();

        return $this->getCurrentQueue();
    }

    public function updateTimeStamps($queue_id, $column){
        $timestamps = QueueStepsTimestamp::where('queue_id', $queue_id)->first();
        if($timestamps){
            if($column == 'recalled_last_at'){
                $timestamps->recall_count += 1;
            }
            $timestamps->{$column} = now();
            $timestamps->save();
        }
        $this->getCurrentQueue();
        return;
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
        }
    }
    public function form1(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('status')
                    ->label('')
                    ->reactive()
                    ->hint('Toggle the station status to go online or offline it.')
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
