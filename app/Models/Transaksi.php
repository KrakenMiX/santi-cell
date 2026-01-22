<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi_topup'; // atau nama tabel kamu di DB
    protected $fillable = [
        'ref_id',
        'buyer_sku_code',
        'customer_no',
        'product_name',
        'price',
        'status',
        'message',
        'sn',
    ];
}
