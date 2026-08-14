<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function branchQueueAllowed()
    {
        return $this->hasMany(BranchQueueAllowed::class);
    }
}
