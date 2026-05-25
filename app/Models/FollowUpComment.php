<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUpComment extends Model
{
    use HasFactory;
    protected $table = 'followupcomment';
    
    protected $fillable = [
        'order_id',
        'uid',
        'comment',
        'commented_by',
    ];

    // Define the relationship with the Order model
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Define the relationship with the User model (if applicable)
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
