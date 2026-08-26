<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APIResponse extends Model
{
    protected $table = 'api_responses';

    protected $fillable = [
        'type',
        'payload',
        'response',
        'is_latest',
        'method',
        'status',
        'url',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_latest' => 'boolean',
        'type' => 'string',
        'status' => 'string',
        'response' => 'string',
        'method' => 'string',
        'url' => 'string',
    ];

}
