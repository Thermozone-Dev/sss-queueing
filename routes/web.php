<?php

// use App\Livewire\CreateQueue;
use Illuminate\Support\Facades\Route;
use App\Livewire\Queues;
use App\Http\Controllers\CreateQueue;
use App\Http\Controllers\ShowQueues;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/queue-board', [ShowQueues::class,'index']);
Route::get('/queue-board/get-queues', [ShowQueues::class,'getQueues'])->name('queues-get');
Route::get('/queue-board/next-inline', [ShowQueues::class,'nextInline'])->name('queues-next');
Route::get('/queue-board/queue-call', [ShowQueues::class,'callNext'])->name('queues-call-next');


Route::get('/queue-kiosk', [CreateQueue::class,'index']);
Route::get('/queue-kiosk/get-station', [CreateQueue::class,'getStations'])->name('get-stations');
Route::get('/queue-kiosk/get-station/{id}', [CreateQueue::class,'getStationTransaction'])->name('get-stations-transaction');
Route::get('/queue-kiosk/get-transaction/{id}', [CreateQueue::class,'getTransaction'])->name('get-transaction');
Route::get('/queue-kiosk/get-priority-type', [CreateQueue::class,'getPriorityType'])->name('get-priority');
Route::post('/queue-kiosk/post-queue', [CreateQueue::class,'store'])->name('queue.post');
Route::get('/queue-kiosk/verify-appointment/{appointment_id}', [CreateQueue::class,'verify_appointment'])->name('appointment.verify');
// Route::post('/queue-kiosk/verify-appointment', [CreateQueue::class,'verify_appointment'])->name('appointment.verify');


Route::get('/search-results', function () {
    return view('search');
});

Route::get('/queues', Queues::class);



