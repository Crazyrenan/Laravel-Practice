<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'nama_menu',
        'link',
        'status',
        'sub_menu',
        'position',
    ];

    // Relationship: One menu can have many sub menus
    public function subMenus()
    {
        return $this->hasMany(SubMenu::class, 'id_menu');
    }
}
