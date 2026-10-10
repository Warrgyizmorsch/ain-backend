<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Next2CallSession extends Model
{
    use HasFactory;

    protected $table = 'next2call_sessions';

    protected $fillable = [
        'user_id',
        'token',
        'webphone_url',
        'click_to_call_url',
        'generated_at',
        'expires_at',
        'agent_status',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at'   => 'datetime',
        'agent_status' => 'integer',
    ];

    /**
     * Check if this session is older than 12 hours or expired.
     */
    public function isExpired(): bool
    {
        if (!$this->generated_at || !$this->expires_at) {
            return true;
        }

        // Check if 12 hours have elapsed since generated_at
        if ($this->generated_at->diffInSeconds(now(), false) >= (12 * 3600)) {
            return true;
        }

        // Also check if expires_at is past (with 120s buffer)
        return now()->addSeconds(120)->greaterThanOrEqualTo($this->expires_at);
    }
}
