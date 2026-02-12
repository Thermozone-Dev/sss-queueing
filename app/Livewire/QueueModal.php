<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\QueueCall;
use App\Models\QueueStepsTimestamp;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QueueModal extends Component
{
    public $queueID;

    public $queue;
    public $showCompleteConfirmationModal;


    public $modal_data;



    public $listeners = [
        'openQueueModal' => 'getQueue',
    ];

    public function mount(): void
    {
        // if($this->queueID){
        //     dd($this->getQueue());
        // }

    }
    public function getQueue(Queue $queueDetails){
        // $this->queue = Queue::find($this->queueID);
        // dd($queueDetails);
        $this->queue = $queueDetails;
        $this->dispatch('open-modal', id: 'queue-modal-view');
    }

    public function recall_queue(){

        $queue_id = $this->queue->id;

        Notification::make()
            ->title('Posted on Board')
            ->icon('fas-bell')
            ->iconColor('success')
            ->color('success')
            ->send();


        $this->updateTimeStamps($queue_id, 'recalled_last_at');
        QueueCall::updateOrCreate(
            ['queue_id' => $queue_id],
            ['is_shown' => false, 'should_remove' => false]
        );
        return;
    }

    // public function call_queue(){
    //     $queue_id = $this->queue->id;
    //     if(!QueueStepsTimestamp::where('queue_id', $queue_id)->first()){
    //         QueueStepsTimestamp::create(
    //             [
    //                 'queue_id' => $queue_id,
    //                 'first_called_at' => now(),
    //             ]);
    //     };
    //     QueueCall::updateOrCreate(
    //         ['queue_id' => $queue_id],
    //         ['is_shown' => false, 'should_remove' => false]
    //     );
    //     return;
    // }

    public function showCompleteModal(){
        $this->showCompleteConfirmationModal = true;
        $this->dispatch('open-modal', id: 'complete-confirmation-modal');

        return;

    }

    public function update_queue($status){
        $queue_id = $this->queue->id;

        $column = null;
        $shouldRemove = false;

        $queue = Queue::findOrFail($queue_id);

        if ($status == 2) {
            $column = 'processed_at';
            $body = 'Queue status set to processing.';
            $color = 'success';
            $shouldRemove = true;
        }

        if ($status == 5) {
            $column = 'removed_at';
            $body = 'Queue status set to removed.';
            $color = 'danger';
            $shouldRemove = true;
        }

        if ($status == 4) {
            $column = 'completed_at';
            $body = 'Queue status set to completed.';
            $color = 'success';
            $shouldRemove = true;
        }

        DB::transaction(function () use ($queue, $status, $column) {

            $this->updateTimeStamps($queue->id, $column);

            $queue->update([
                'status_id' => $status,
            ]);

            if ($status == 4) {
                $queue->moveToNextStep();
            }
        });
        // /Users/dennisenraca/Herd/sss-queueing/images/default_front_end/logo-light-text.png
        $queue_call = QueueCall::where('queue_id', $queue_id)->first();
        if ($queue_call) {
            $queue_call->should_remove = $shouldRemove;
            $queue_call->save();
        }

        if (!$this->station->pendingQueues()->first()) {
            $body = 'There are no more queues to process.';
            $title = 'Queue Empty';
        }

        Notification::make()
            ->title($title ?? 'Queue Updated')
            ->body($body)
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
        // $this->getCurrentQueue();
        return;
    }

    public function render()
    {
        return view('livewire.queue-modal');
    }
}
