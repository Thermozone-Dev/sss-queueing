<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'queues';

	protected $fillable = [
        'queue_number',
		'name',
        'mobile_num',
        'last_transacted_by',
        'transaction_id',
        'priority_type',
        'status_id',
	];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_type');
    }

    public function status()
    {
        return $this->belongsTo(QueueStatus::class, 'status_id');
    }

    public function getQueueNumber()
    {
        return $this->transaction->station->code. '-' . str_pad($this->queue_number, 4, '0', STR_PAD_LEFT);
    }
}
