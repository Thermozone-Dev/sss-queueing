<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionStepStation extends Model
{
    protected $table = 'transaction_step_stations';

    protected $fillable = [
        'branch_id',
        'transaction_id',
        'transaction_step_id',
        'station_id',
    ];

    protected $casts = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionStep()
    {
        return $this->belongsTo(TransactionStep::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
