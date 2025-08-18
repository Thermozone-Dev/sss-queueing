<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueStatus extends Model
{
    public $timestamps = false;

    protected $table = 'queue_statuses';

	protected $fillable = [ 
		'name',
	];

}
