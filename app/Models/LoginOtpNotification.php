<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginOtpNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp_code',
        'ip_address',
        'user_agent',
        'status',
        'purpose',
        'email_to',
        'failed_attempts',
        'last_failed_at',
        'blocked_at',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'last_failed_at' => 'datetime',
        'blocked_at' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isUsable(): bool
    {
        return $this->status === 'pending'
            && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
