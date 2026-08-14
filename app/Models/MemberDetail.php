<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDetail extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'sss_number',
        'birth_date',
        'gender',
        'mobile_number',
        'is_senior',
        'is_pwd',
        'status',
        'last_synced_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_senior' => 'boolean',
        'is_pwd' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}