<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'request'; // Laravel assumes 'requests' by default, so this line is needed

    protected $fillable = [
        'name',
        'email',
        'phone',
        'department',
        'status',
    ];
}
