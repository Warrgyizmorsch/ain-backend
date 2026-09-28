<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Next2CallLog extends Model
{
    protected $table = 'next2call_call_logs';

    protected $fillable = [
        'next2call_id',
        'uniqueid',
        'did',
        'direction',
        'status',
        'call_from',
        'call_to',
        'customer_name',
        'duration',
        'duration_formatted',
        'hangup',
        'campaign_id',
        'record_url',
        'local_record_path',
        'agent_user_id',
        'agent_id',
        'started_at',
        'ended_at',
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

    public function getStatusLabelAttribute(): string
    {
        return match(strtoupper($this->status)) {
            'ANSWER'     => 'Answered',
            'CANCEL'     => 'Cancelled',
            'NOANSWER'   => 'Missed / No Answer',
            'CONGESTION' => 'Congestion',
            default      => ucfirst(strtolower($this->status)),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match(strtoupper($this->status)) {
            'ANSWER'     => 'badge-light-success text-success',
            'CANCEL'     => 'badge-light-warning text-warning',
            'NOANSWER'   => 'badge-light-danger text-danger',
            'CONGESTION' => 'badge-light-dark text-dark',
            default      => 'badge-light-secondary text-muted',
        };
    }

    /**
     * Get playable audio URL: local archived file if available, or direct Next2Call record_url
     */
    public function getPlayableRecordUrlAttribute(): ?string
    {
        if (!empty($this->local_record_path) && file_exists(public_path($this->local_record_path))) {
            return asset($this->local_record_path);
        }
        return $this->record_url;
    }
}
