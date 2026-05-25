<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $table = 'user_logs'; // table name

    protected $fillable = [
        'user_id',
        'module',
        'action',
    ];
    protected $casts = [
    'action' => 'array', 
    ];

    // 🔗 Relation (optional but useful)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}