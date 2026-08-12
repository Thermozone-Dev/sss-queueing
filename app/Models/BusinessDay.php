<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDay extends Model
{
    protected $fillable = [
        'branch_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
