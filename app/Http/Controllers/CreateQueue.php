<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\Priority;
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

    //
}
