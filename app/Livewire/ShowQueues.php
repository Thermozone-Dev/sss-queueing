<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\Station;
use Livewire\Component;
use Livewire\Attributes\Lazy;


class ShowQueues extends Component
{
    public $showModal = false;

    public $queues;
    public $now_serving;

    public function mount(): void
    {
        $this->getQueues();
        $this->getNowServing();
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
        $colors = ['bg-sky-800','bg-teal-500','bg-green-400'];
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
        // $this->dispatch('open-modal', id: 'edit-user');
    }



}
