<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceGame extends Model
{
    protected $table = 'prices_game';

    protected $fillable = [
        'code',
        'game',
        'name',
        'price_basic',
        'price_premium',
        'price_special',
        'server',
        'status',
    ];
}
