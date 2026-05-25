<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'priority',
        'percentage',
        'created_by',
        'is_delete',
        'member_ids',
    ];
    public function members()
    {
        return $this->hasMany(User::class)->where('role_id', 4);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
