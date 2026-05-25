<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'mobile_no',
        'countrycode',
        'team_id',
        'Wallet',
        'verifyed',
        'otp'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $attributes = [
        'role_id' => 2,
    ];
    
     public function writerWork()
    {
        return $this->hasMany(multipleswiter::class, 'user_id', 'id')->with('order');
    }
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function orders() {
        return $this->hasMany(Order::class, 'uid', 'id');
    }

    public function followups()
    {
        // Yahan 'Followup::class' ko apne actual follow-up model se replace karein
        return $this->hasMany(FollowUpComment::class, 'uid'); 
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'refer_id', 'id');
    }
}
