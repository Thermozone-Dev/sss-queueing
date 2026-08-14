<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationTransaction extends Model
{
    protected $table = 'station_transactions';

    protected $fillable = [
        'station_id',
        'transaction_id',
    ];

    protected $casts = [];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
