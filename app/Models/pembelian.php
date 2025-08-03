<?php

namespace App\Models;

use Illuminate\Cache\HasCacheLock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian'; 
    protected $fillable = [
        'vendor_id',
        'project_id',
        'requested_by',
        'purchase_order_number',
        'item_name',
        'item_code',
        'category',
        'quantity',
        'unit',
        'buy_price',
        'unit_price',
        'total_price',
        'tax',
        'grand_total',
        'purchase_date',
        'expected_delivery_date',
        'status',
        'remarks',
    
    ];
    protected $casts = [
    'purchase_date' => 'date:Y-m-d',
    'expected_delivery_date' => 'date:Y-m-d',
    ];

    
}
