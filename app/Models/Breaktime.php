<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breaktime extends Model
{
    protected $table = 'break_times';

    protected $fillable = [
        'branch_id',
        'from',
        'to',
    ];

    protected $casts = [
        'from' => 'string',
        'to' => 'string',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
