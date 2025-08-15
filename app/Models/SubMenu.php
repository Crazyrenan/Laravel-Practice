<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_menu',
        'icon',
        'nama',
        'link',
        'status',
        'position',
    ];

    // Relationship: A sub menu belongs to one menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
