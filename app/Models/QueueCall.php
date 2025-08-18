<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueCall extends Model
{
    public $timestamps = false;

    protected $table = 'queue_calls';

	protected $fillable = [
		'queue_id',
		'is_shown',
        'should_remove',
	];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
