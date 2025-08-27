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

     public function scopeActive($query)
     {
         return $query
                ->applySorting()
                // ->whereDate('queues.created_at', Carbon::yesterday()) //test
                ->whereNotIn('status_id', [4, 5]);
     }

    public function scopeApplySorting($query)
    {
        return $query->orderByRaw('CASE WHEN priority_type IS NOT NULL THEN 0 ELSE 1 END')
                ->whereDate('queues.created_at', Carbon::yesterday())
                ->orderBy('queues.created_at', 'asc');
    }

    public function scopePending($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status_id', 2);
    }




}
