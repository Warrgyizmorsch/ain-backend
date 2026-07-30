<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = [
        'customer_type'
    ];

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
        'otp',
        'photo'
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

    public function leads()
    {
        return $this->hasMany(Leads::class, 'emp_id', 'id');
    }
    public function groups() { return $this->belongsToMany(GroupMaster::class)->withTimestamps(); }

    public function followups()
    {
        // Yahan 'Followup::class' ko apne actual follow-up model se replace karein
        return $this->hasMany(FollowUpComment::class, 'uid'); 
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'refer_id', 'id');
    }

    /**
     * Dynamic Customer Categorization:
     * - Loyal Customer: Total orders > 10
     * - Retainer Customer: First order was 9+ months ago (and > 1 orders)
     * - Repeated Customer: > 1 orders within 3 months of first order
     * - Beginner Customer: 1 order
     * - New Customer: 0 orders
     */
    public function getCustomerTypeAttribute()
    {
        $ordersCount = $this->orders()->count();
        if ($ordersCount === 0) {
            return 'New Customer';
        }

        // 1. Loyal Customer (> 10 Orders)
        if ($ordersCount > 10) {
            return 'Loyal Customer';
        }

        $firstOrder = $this->orders()->oldest('created_at')->first();
        if (!$firstOrder) {
            return 'New Customer';
        }

        $firstOrderDate = Carbon::parse($firstOrder->created_at);
        $monthsSinceFirstOrder = $firstOrderDate->diffInMonths(now());

        // 2. Retainer Customer (First order placed 9+ months ago AND has repeated purchases)
        if ($monthsSinceFirstOrder >= 9 && $ordersCount > 1) {
            return 'Retainer Customer';
        }

        // 3. Repeated Customer (> 1 orders & placed orders within 3 months)
        if ($ordersCount > 1 && $monthsSinceFirstOrder <= 3) {
            return 'Repeated Customer';
        }

        // 4. Beginner Customer (1 order or default)
        return 'Beginner Customer';
    }
}
