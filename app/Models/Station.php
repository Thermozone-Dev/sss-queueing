<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BranchScoped;

/**
 * Class Station
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $type
 * @property bool $status
 * @property bool $priority_handling
 * @property int $max_concurrent_clients
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Station extends Model
{
	protected $table = 'stations';
    use SoftDeletes;
    use BranchScoped;

	protected $casts = [
		'status' => 'bool',
		'priority_handling' => 'bool',
		'max_concurrent_clients' => 'int'
	];

	protected $fillable = [
		'branch_id',
		'name',
		'code',
		'type',
		'status',
        'icon',
        'description',
		'priority_handling',
		'max_concurrent_clients'
	];

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public function queues($stationID)
    {
        // dd($this->id);
        $steps = QueueStep::with('queue')
            ->where('station_id', $stationID)
            // ->where('station_id', 3)
            ->join('queues', 'queues.id', '=', 'queue_steps.queue_id')
            ->whereDate('queues.created_at', Carbon::today())
            ->orderByRaw("
                CASE
                    WHEN queues.external_appointments IS NOT NULL THEN 0
                    WHEN queues.priority_type IS NOT NULL THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('queues.created_at', 'asc')
            ->select('queue_steps.*');

        $active = (clone $steps)->pendingSteps();
        $done = (clone $steps)->completedSteps();
        return[
            'active' => $active,
            'done' => $done,
        ];
    }

    public function activeQueues()
    {
        $stationID = $this->id;
        return $this->queues($stationID)['active'];
    }

    public function pendingQueues(){
        return $this->activeQueues()->where('queue_step_status_id', 1);
    }

    public function processingQueues(){
        // dd($this->activeQueues()->get());
        return $this->activeQueues()->where('queue_step_status_id', 2);
    }

    public function doneQueues(){
        $stationID = $this->id;
        return $this->queues($stationID)['active'];;
    }
}
