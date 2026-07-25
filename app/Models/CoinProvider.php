<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinProvider extends Model
{
    protected $fillable = [
        'name',
        'user_type',
        'whatsapp',
        'address',
        'latitude',
        'longitude',
        'total_coins',
        'denominations',
        'operational_hours',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'denominations' => 'array',
        'is_active'     => 'boolean',
        'latitude'      => 'float',
        'longitude'     => 'float',
        'total_coins'   => 'integer',
    ];
}
