<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BranchScoped;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalAppointments extends Model
{
    protected $table = 'external_appointments';

    use BranchScoped;

    protected $fillable = [
        'branch_id',
        'status',
        'appointment_code',
        'date',
        'time',
        'raw_response'
    ];

    public function transaction() : BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function status_name() : BelongsTo
    {
        return $this->belongsTo(QueueStatus::class, 'status');
    }

    //
}
