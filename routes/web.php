<?php

// use App\Livewire\CreateQueue;
use Illuminate\Support\Facades\Route;
use App\Livewire\Queues;
use App\Livewire\ShowQueues;
use App\Http\Controllers\CreateQueue;

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

Route::get('/queue-board', ShowQueues::class);

Route::get('/queue-kiosk', [CreateQueue::class,'index']);
Route::get('/queue-kiosk/get-station', [CreateQueue::class,'getStations'])->name('get-stations');
Route::get('/queue-kiosk/get-station/{id}', [CreateQueue::class,'getStationTransaction'])->name('get-stations-transaction');
Route::get('/queue-kiosk/get-transaction/{id}', [CreateQueue::class,'getTransaction'])->name('get-transaction');
Route::get('/queue-kiosk/get-priority-type', [CreateQueue::class,'getPriorityType'])->name('get-priority');
Route::post('/queue-kiosk/post-queue', [CreateQueue::class,'store'])->name('queue.post');


Route::get('/search-results', function () {
    return view('search');
});

Route::get('/queues', Queues::class);



