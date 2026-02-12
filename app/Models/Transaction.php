<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Traits\BranchScoped;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @property int $id
 * @property int $station_id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Station $station
 * @property Collection|TransactionStep[] $transaction_steps
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';
    use BranchScoped;

	protected $casts = [
		'station_id' => 'int'
	];

	protected $fillable = [
		'branch_id',
		'station_id',
		'name',
		'code',
		'description',
        'required_documents'
	];

	public function station()
	{
		return $this->belongsTo(Station::class);
	}

	public function transaction_steps()
	{
		return $this->hasMany(TransactionStep::class)->orderBy('sort_order');
	}

    public function firstStep()
    {
        return $this->transaction_steps()->orderBy('sort_order')->first();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class,'transaction_id');
    }

    public function active_queues($query)
    {
        return $query->whereHas('queues', function ($q) {
            $q->where('status_id', 1);
        });
    }
}
