<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueStep extends Model
{
    public $timestamps = false;

    protected $table = 'queue_steps';

	protected $fillable = [
		'queue_id',
		'eligible_start_time',
        'transaction_step_id',
        'station_id',
        'queue_step_status_id',
	];

    public function queue()
	{
		return $this->belongsTo(Queue::class);
	}
    public function station()
	{
		return $this->belongsTo(Station::class);
	}

    public function transaction_step()
	{
		return $this->belongsTo(TransactionStep::class,'transaction_step_id');
	}

    public function status()
	{
		return $this->belongsTo(QueueStatus::class,'queue_step_status_id');
	}
}
