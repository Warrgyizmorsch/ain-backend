<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function servicePages()
    {
        return $this->hasMany(ServicePage::class);
    }
}
