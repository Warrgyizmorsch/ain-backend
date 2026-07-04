<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginOtpSystemBan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_id',
        'banned_until',
        'is_manual',
        'attempts_count',
        'last_attempt_at',
        'reason',
        'banned_by',
        'unbanned_at',
        'unbanned_by',
    ];

    protected $casts = [
        'banned_until' => 'datetime',
        'is_manual' => 'boolean',
        'last_attempt_at' => 'datetime',
        'unbanned_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return !$this->unbanned_at
            && (!$this->banned_until || $this->banned_until->isFuture());
    }
}
