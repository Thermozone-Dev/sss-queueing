<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BusinessDay extends Model
{
    protected $fillable = [
        'branch_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected function operatingDays(): Attribute
    {
        return Attribute::make(
            get: function () {
                $days = [];
                $dayNames = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

                foreach ($dayNames as $index => $day) {
                    if ($this->$day) {
                        $days[] = $dayLabels[$index];
                    }
                }

                return implode(', ', $days) ?: 'No days configured';
            }
        );
    }
}
