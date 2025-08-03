<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    protected $fillable = [
        'customer_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'total_price',
        'purchase_date',
        'year',
        
    ];
}
