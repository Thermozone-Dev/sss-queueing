<?php

namespace App\Http\Controllers;

use App\Models\ExternalAppointments;
use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\Priority;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Queue;
use Illuminate\Support\Facades\Http;
use App\Models\QueueStep;
use Illuminate\Support\Facades\DB;

class CreateQueue extends Controller
{


    private string $apiUrl = 'https://raw.githubusercontent.com/Dennis-Enraca-School/WebSysAPI/refs/heads/main/sss-appointment-sample.json'; //soon to be added on .env


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
            $transactions = $station->transactions()
                    ->with(['transaction_steps' => function ($query) {
                        $query->orderBy('sort_order', 'asc');
                    }])->get();

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
                'required_documents' => nl2br($transaction->required_documents),
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

            // dd($request);
            $validated = $request->validate([
                'name' => ['required', 'string'],
                'mobile' => ['nullable', 'regex:/^09\d{9}$/'],
                'priority_type' => ['nullable', 'in:1,2,3'],
                'appointment_id' => ['nullable'],
            ]);
            $appointment = null;
            if(request()->has('appointment_id') && !empty(request('appointment_id'))){
                $appointmentVerification = $this->verify_appointment(request('appointment_id'));
                if($appointmentVerification->getData()->success === false){
                    return response()->json([
                        'status' => 'error',
                        'message' => $appointmentVerification->getData()->message
                    ], 400);
                }else{
                    $appointment = $appointmentVerification->getData()->data;
                }
            }
            $external_appointment = null;
            if($appointment){
                $external_appointment = ExternalAppointments::create([
                    'appointment_code' => $appointment?->code ?? null,
                    'date' => $appointment?->date ?? null,
                    'time' => $appointment?->time ?? null,
                    'branch_id' => $appointment?->branch_id ?? null,
                    'raw_response' => json_encode($appointment),
                ]);
            }
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
                // 'transaction_step_id' => optional($transaction->firstStep())->id,
                'name' => substr(str_replace(' ', '', $request->name), 0, 8),
                'mobile_num' => $request->mobile,
                'priority_type' => $request->priority_type ?? null,
                'external_appointments' => $external_appointment?->id ?? null

            ]);

            foreach($transaction->transaction_steps as $step)
            {
                QueueStep::insert([
                    'queue_id' => $queue_details->id,
                    'eligible_start_time' => null,
                    'transaction_step_id' => $step->id,
                    'queue_step_status_id' => $step->is_required ? 1 : 4, // based_on_step if should be lined up the status default was 1 (Waiting), else 4 (Completed)
                    'station_id' => $step->linked_station_id ?? $step->transaction->station_id,
                ]);
            }

            $data = [
                'transaction_name' => $transaction->name,
                'queue_number' => $queue_details->getQueueNumber(),
            ];

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function fetch_appointments()
    {
        $response = Http::timeout(10)->get($this->apiUrl);

        if (!$response->successful()) {
            return collect();
        }

        return collect($response->json())->map(function ($item) {
            return [
                'appointment_id' => $item['appointment_id'],
                'code'           => $item['code'],
                'name'           => $item['name'],
                'date'           => $item['date'],
                'time'           => $item['time'],
                'status'         => $item['status'],
                'branch_id'      => $item['branch_id'],
            ];
        });
    }


    public function search_appointment($appointmentID)
    {

        return $this->fetch_appointments()
            ->firstWhere('code', $appointmentID);
    }

    public function has_already_transacted(){
        $appointmentID = request('appointment_id');
        if(!$appointmentID){
            return false;
        }
        $appointment = ExternalAppointments::where('appointment_code', $appointmentID)->first();
        return $appointment ? true : false;

    }

    public function verify_appointment($appointment_id)
    {
        $appointment = $this->search_appointment($appointment_id);

        if($this->has_already_transacted($appointment_id)){
            return response()->json([
                'success' => false,
                'message' => 'Appointment has already been transacted'
            ], 404);
        }


        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found'
            ], 404);
        }

        if ($appointment['status'] === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Appointment is cancelled'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }



    //
}
