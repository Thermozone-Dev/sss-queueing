<?php

namespace App\Models;

use App\Models\Traits\BranchScoped;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Queue extends Model
{
    protected $table = 'queues';

    use BranchScoped;

	protected $fillable = [
        'queue_number',
		'name',
        'mobile_num',
        'last_transacted_by',
        'transaction_id',
        'priority_type',
        'status_id',
        'transaction_step_id',
        'branch_id',
        'external_appointments'
	];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function appointment()
    {
        return $this->belongsTo(ExternalAppointments::class, 'external_appointments');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function step()
    {
        return $this->belongsTo(TransactionStep::class, 'transaction_step_id');
    }

    public function currentStation()
    {
        return $this->step
            ? $this->step->linked_station
            : $this->transaction->station;
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_type');
    }

    public function status()
    {
        return $this->belongsTo(QueueStatus::class, 'status_id');
    }

    public function getQueueNumber()
    {
        return $this->transaction->station->code. '-' . str_pad($this->queue_number, 4, '0', STR_PAD_LEFT);
    }

    public function scopeActive($query)
    {
        return $query
            ->applySorting()
            // ->whereDate('queues.created_at', Carbon::yesterday()) //test
            ->whereNotIn('status_id', [4, 5]);
    }

    public function scopeApplySorting($query)
    {
        return $query
            ->whereDate('queues.created_at', Carbon::today())
            ->orderByRaw("
                CASE
                    WHEN external_appointments IS NOT NULL THEN 0
                    WHEN priority_type IS NOT NULL THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('queues.created_at', 'asc');
    }
    public function scopePending($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status_id', 2);
    }


    public function scopePriority($query){
        return $query->whereNotNull('priority_type');
    }

    public function scopeAppointment($query){
        return $query->whereNotNull('external_appointments');
    }

    public function queueSteps() : HasMany
    {
        return $this->hasMany(QueueStep::class, 'queue_id');
    }

    public function getCurrentLine()
    {
        return $this->queueSteps()->where('queue_step_status_id', 1)->first();
    }

    public function updateStatusIfCompleted()
    {
        $hasRemaining = $this->queueSteps()
            ->whereHas('step', fn($q) => $q->where('is_required', true))
            ->whereNotIn('queue_step_status_id', [4,3,5]) //complete, remove, paused
            ->exists();

        if (! $hasRemaining) {
            $this->update(['status_id' => 4]);
        }
    }
    public function getLaneTypeAttribute(){
        if($this->external_appointments){
            return 'Appointment';
        }
        if($this->priority){
            return 'Priority';
        }
        return 'Regular';
    }


}
