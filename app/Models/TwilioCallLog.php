<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwilioCallLog extends Model
{
    protected $table = 'twilio_call_logs';

    protected $fillable = [
        'call_sid',
        'direction',
        'status',
        'from_number',
        'to_number',
        'customer_name',
        'duration',
        'agent_user_id',
        'agent_identity',
        'started_at',
        'ended_at',
        'notes',
        'recording_url',
        'recording_sid',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'duration'   => 'integer',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    /**
     * Human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'completed'   => 'Completed',
            'missed'      => 'Missed',
            'no-answer'   => 'No Answer',
            'failed'      => 'Failed',
            'cancelled'   => 'Cancelled',
            'in-progress' => 'In Progress',
            'ringing'     => 'Ringing',
            default       => ucfirst($this->status),
        };
    }

    /**
     * Badge color class for status
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'completed'   => 'badge-light-success',
            'missed'      => 'badge-light-danger',
            'no-answer'   => 'badge-light-warning',
            'failed'      => 'badge-light-danger',
            'cancelled'   => 'badge-light-secondary',
            'in-progress' => 'badge-light-info',
            'ringing'     => 'badge-light-primary',
            default       => 'badge-light-primary',
        };
    }

    /**
     * Formatted duration: MM:SS
     */
    public function getFormattedDurationAttribute(): string
    {
        $d = (int) $this->duration;
        return sprintf('%02d:%02d', intdiv($d, 60), $d % 60);
    }
}
