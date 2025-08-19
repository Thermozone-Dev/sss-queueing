<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\QueueCall;
use App\Models\Station;
use Livewire\Component;
use Livewire\Attributes\Lazy;


class ShowQueues extends Component
{
    public $showModal = false;

    public $modalDetails;

    public $queues;
    public $now_serving;
    public $time_now;

    public function mount(): void
    {
        $this->getQueues();
        $this->getNowServing();
        $this->getTime();
        $this->modalDetails = null;
    }

    public function getTime(){
        $this->time_now = [
            'time' => now()->format('H:i A'),
            'date' => now()->format('l, d F Y'),
        ];
    }

    public function getQueues(){
        $stations = Station::all();
        $queues = [];
        foreach ($stations as $station){
            $activeQueues = $station->pendingQueues->take(5);
            $queues[] = [
                'station' => $station,
                'queues' => $activeQueues,
            ];
        }
        return $this->queues = $queues;
    }


    public function getNowServing(){
        $onProcess_queues = Queue::active()
                        ->processing()
                        ->get()->take(3);
        $this->now_serving = [];
        $colors = ['#075985','#14B8A6','#4ADE80'];
        $counter = 0;
        foreach ($onProcess_queues as $process){
            $this->now_serving[]= [
                'stations_name' => $process->transaction->station->name,
                'name' => $process->name,
                'queue_number' => $process->getQueueNumber(),
                'station_code' => $process->transaction->code,
                'bg_color' => $colors[$counter],
            ];
            $counter++;
            if($counter == 4){
                $counter = 0;
            }

        }
        return $this->now_serving;
    }

    public function gather_queue_calls(){
        $this->getQueues();
        $this->getNowServing();
        $call = QueueCall::orderBy('id','asc')->where('is_shown',false)->first();
        $removed_queue = QueueCall::where('is_shown',false)->get();
        if($removed_queue->count() > 0){
            foreach ($removed_queue as $queue){
                if($queue->should_remove){
                    $queue->delete();
                }
            }
        }

        if(!$call){
            return;
        }
        $this->modalDetails = [
            'queue_number' => $call->queue->getQueueNumber(),
            'transaction' => $call->queue->transaction->name,
        ];
        $this->call_number();
        $call->is_shown = true;
        $call->update();
        return $this->modalDetails;
    }


    public function render()
    {
        return view('public_pages.queue-board');
    }

    public function call_number(){
        $this->showModal = true;
        $this->dispatch('open-modal');
    }

    public function closeModal(){
        $this->showModal = false;
    }



}
