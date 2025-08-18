<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueStepsTimestamp extends Model
{

    public $timestamps = false;

    protected $table = 'queue_steps_timestamps';

	protected $fillable = [
		'queue_id',
		'first_called_at',
		'recalled_last_at',
		'recall_count',
		'removed_at',
		'processed_at',
		'skipped_at',
		'completed_at',
	];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

}
