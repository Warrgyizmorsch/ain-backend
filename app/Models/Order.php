<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
protected $table = 'orders';
        protected $fillable = [
        'uid',
        'wid',
        'swid',
        'order_id',
        'status',
        'title',
        'description',
        'amount',
        'currency',
        'created_at',
        'updated_at',
        'lead_id',
        'team_id',
        'team_assigned_at',
        'marks',
        'offer',
    ];
    
     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'order_id', 'id');

    }


    public function ordercall()
    {
        return $this->hasmany(Ordercall::class, 'order_id', 'id')->with('user');
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'wid');
    }

    public function subwriter()
    {
        return $this->belongsTo(User::class, 'swid');
    }

    public function mulsubwriter()
    {
        return $this->hasMany(multipleswiter::class, 'order_id', 'id')->with('user');
    }
    
       public function order()
    {
        return $this->belongsTo(multipleswiter::class, 'order_id');
    }
     public function followUpComments()
    {
        return $this->hasMany(FollowUpComment::class, 'order_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function lead()
{
    return $this->belongsTo(Leads::class, 'lead_id');
}

    public function additionals()
    {
        return $this->hasMany(\App\Models\Additional::class, 'order_id', 'order_id');
    }
}
