<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\Priority;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Queue;

class CreateQueue extends Controller
{
    public function index()
    {
        return view('public_pages.tablet-view');
    }


    public function getStations()
    {
        try {
            $stations = Station::all();
            return response()->json([
                'status' => 'success',
                'data' => $stations
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch stations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getStationTransaction($id){

          try {
            $station = Station::findOrFail($id);
            $transactions = $station->transactions()->get();

            return response()->json([
                'status' => 'success',
                'station_name' => $station->name,
                'data' => $transactions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch stations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTransaction($id){
          try {
            $transaction = \App\Models\Transaction::findOrFail($id);
            $data = [
                'id' => $transaction->id,
                'name' => $transaction->name,
                'station' => $transaction->station->name,
                'description' => nl2br($transaction->description),
                'steps' => $transaction->transaction_steps->sortBy('sort_order')
            ];
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     public function getPriorityType()
    {
        try {
            $priorityType = Priority::orderBy('name','asc')->get();
            return response()->json([
                'status' => 'success',
                'data' => $priorityType
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch stations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request){
        try {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:8'],
                'mobile' => ['nullable', 'regex:/^09\d{9}$/'],
                'priority_type' => ['nullable', 'in:1,2,3'],
            ]);

            $today = Carbon::today();

            // Get the transaction (must have a 'code' column like 'BP', 'C', etc.)
            $transaction = Transaction::findOrFail($request->transaction_id);
            $stationCode = $transaction->station->code;

            // Get last queue for this station today
            $lastQueue = Queue::whereDate('created_at', $today)
                ->whereHas('transaction.station', function ($query) use ($stationCode) {
                    $query->where('code', $stationCode);
                })
                ->orderBy('id', 'desc')
                ->first();
            if ($lastQueue) {
                $lastNumber = (int) $lastQueue->queue_number;
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            $queueNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $queue_details = Queue::create([
                'queue_number' => $queueNumber,
                'transaction_id' => $transaction->id,
                'name' => $request->name,
                'mobile_num' => $request->mobile,
                'priority_type' => $request->priority_type ?? null
            ]);
            $data = [
                'transaction_name' => $transaction->name,
                'queue_number' => $queue_details->getQueueNumber(),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return $e;
        }
    }

    //
}
