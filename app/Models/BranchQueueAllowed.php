<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchQueueAllowed extends Model
{
    protected $table = 'branch_queue_allowed';

    protected $fillable = [
        'branch_id',
        'queue_type_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function queueType()
    {
        return $this->belongsTo(QueueType::class);
    }
}
