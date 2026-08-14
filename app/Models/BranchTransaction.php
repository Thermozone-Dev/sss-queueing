<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchTransaction extends Model
{
    protected $table = 'branch_transactions';

    protected $fillable = [
        'branch_id',
        'transaction_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
