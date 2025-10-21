<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Station;
use App\Models\QueueCall;

class ShowQueues extends Controller
{
    public function index()
    {
        return view('public_pages.queue-board-vue');
    }

    public function getQueues(){

        try {
            $onProcess_queues = Queue::active()
                    ->processing()
                    ->get()->take(3);

            $now_serving = [];
            $colors = ['#075985','#14B8A6','#4ADE80'];
            $counter = 0;
            foreach ($onProcess_queues as $process){
                $now_serving[]= [
                    'id' => $process->id,
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

            return response()->json([
                'status' => 'success',
                'data' => $now_serving
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch stations',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    public function nextInline(){
        try {

            $stations = Station::orderBy('status','desc')->get();
            $queues = [];

            foreach ($stations as $station){
                $processing = $station->processingQueues->first();
                $activeQueues = $station->pendingQueues->take(5);
                $q = [];
                $q = $activeQueues->map(function ($map){
                    return [
                        'name' => $map->name,
                        'transaction_name' => $map->transaction->name,
                        'queue_number' => $map->getQueueNumber(),
                    ];
                });
                $queues[] = [
                    'id' => $station->id,
                    'status' => $station->status,

                    'processing' => $processing ? [
                        'name' => $processing->name,
                        'transaction_name' => $processing->transaction->name,
                        'queue_number' => $processing->getQueueNumber(),
                    ] : null,
                    'station' => $station->name ?? $station->code,
                    'queues' => array_chunk($q->toArray(),3),
                ];
            }
            // dd($queues);
            return response()->json([
                'status' => 'success',
                'data' => $queues
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch stations',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function callNext(){
        try {
            $call = QueueCall::orderBy('id','asc')->where('is_shown',false)->first();
            $removed_queue = QueueCall::where('should_remove',true)->get();
            if($removed_queue->count() > 0){
                foreach ($removed_queue as $queue){
                    if($queue->should_remove){
                        $queue->delete();
                    }
                }
            }
            if(!$call){
                return response()->json([
                    'status' => 'empty',
                    'data' => []
                ], 200);
            }
            $call->is_shown = true;
            $call->update();
            $call_details = [
                'queue_number' => $call->queue->getQueueNumber(),
                'transaction' => $call->queue->transaction->name,
                'name' => $call->queue->name,

            ];
            return response()->json([
                'status' => 'success',
                'data' => $call_details
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch stations',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    //
}
