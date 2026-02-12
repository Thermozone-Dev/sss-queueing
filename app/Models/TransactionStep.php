<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionStep
 *
 * @property int $id
 * @property int $transaction_id
 * @property string $title
 * @property string|null $description
 * @property bool $is_required
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class TransactionStep extends Model
{
	protected $table = 'transaction_steps';

	protected $casts = [
		'transaction_id' => 'int',
		'is_required' => 'bool',
		'sort_order' => 'int'
	];

	protected $fillable = [
		'transaction_id',
		'title',
		'description',
		'linked_station_id',
		'is_required',
		'sort_order'
	];

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}


    public function linked_station()
	{
		return $this->belongsTo(Station::class,'linked_station_id');
	}

}
