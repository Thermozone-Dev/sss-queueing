<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

	protected $casts = [
		'status' => 'bool',
		'priority_handling' => 'bool',
		'max_concurrent_clients' => 'int'
	];

	protected $fillable = [
		'name',
		'code',
		'type',
		'status',
        'icon',
		'priority_handling',
		'max_concurrent_clients'
	];

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
