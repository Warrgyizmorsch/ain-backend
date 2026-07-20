<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    use HasFactory;
    protected $table = 'menu';

    public function Submenus()
    {
        return $this->hasMany(Submenus::class, 'menus_id');
    }

    public function parent()
    {
        return $this->belongsTo(menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(menu::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    
}
