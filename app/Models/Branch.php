<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'opening_hours',
        'closing_hours',
        'address_line_1',
        'address_line_2',
        'city',
        'province',
        'postal_code',
        'email',
        'contact_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function businessDay()
    {
        return $this->hasOne(BusinessDay::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(Breaktime::class);
    }

    public function disabledDates()
    {
        return $this->hasMany(BranchDisabledDate::class);
    }

    public function queueAllowed()
    {
        return $this->hasMany(BranchQueueAllowed::class);
    }

    public function queueTypes()
    {
        return $this->belongsToMany(
            QueueType::class,
            'branch_queue_allowed',
            'branch_id',
            'queue_type_id'
        )->withTimestamps();
    }

    public function branchTransactions()
    {
        return $this->hasMany(BranchTransaction::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'branch_transactions')
            ->withPivot('is_active')
            ->withTimestamps();
    }
}


/*

    Branch
    For Head Office
        - must only select a branch from API
    code
    name
    opening_hours
    closing_hours
    address_line_1
    address_line_2
    city
    province
    postal_code
    email
    contact_number
    is_active (bool)

    API_Responses
        - type_id (enum: 1.Branch 2. Transaction 3. Member)
        - payload
        - is_sync_to_model

    Branch has Breaktime
        - branch_id (FK) (Cascade on Delete)
        - from
        - to

    Branch has Transaction
        - branch_id
        - transaction_id
        - is_active (bool)

    Business Days
        - branch_id (FK) (Cascade on Delete)
        - monday (bool) (default:true)
        - tuesday (bool) (default:true)
        - wednesday (bool) (default:true)
        - thursday (bool) (default:true)
        - friday (bool) (default:true)
        - saturday (bool) (default:false)
        - sunday (bool) (default:false)

    Branch has Disabled Date - For Holidays
        - branch_id (FK) (Cascade on Delete)
        - date
        - notes (nullable)

    Queue Type
        - Walk In
        - Appointment
        - Priority Lanes

    Branch Queue Allowed
        - branch_id (FK) (Cascade on Delete)
        - queue_type_id (FK) (Cascade on Delete)

    Transaction
        // I will be dropping branch ID and Station ID
        - branch_id
        - transaction_code

    Branch has Transactions
        - branch_id (FK) (Cascade on Delete)
        - transaction_id

    Transaction
        - branch_id
        - code
        - name
        - description
        - category

    Station has Transaction

    Transaction Steps has Station
      // this is where we save the transaction steps and assigned station for that steps
        - branch ID
        - transaction id
        - station id

    Transaction Resource
        - fetching a transaction from API
        - saving the Transaction from API to model
        - checks for all transaction that does not exists to platform
        - make sure that all transaction from API is saved to platform
        - transction configuration for steps

*/
