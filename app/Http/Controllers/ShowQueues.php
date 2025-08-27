<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Station;

class ShowQueues extends Controller
{
    public function index()
    {
        return view('public_pages.queue-board-vue');
    }

    public function getQueues(){

        try {
            $onProcess_queues = Queue::active()
                        // ->processing()
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

            $stations = Station::all();
            $queues = [];

            foreach ($stations as $station){
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
                    'station' => $station->name ?? $station->code,
                    'queues' => $q->toArray(),
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


    //
}
