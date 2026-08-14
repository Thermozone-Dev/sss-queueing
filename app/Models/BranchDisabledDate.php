<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchDisabledDate extends Model
{
    protected $table = 'branch_disabled_dates';

    protected $fillable = [
        'branch_id',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
