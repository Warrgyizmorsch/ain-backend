<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $table = 'email_campaigns';

    protected $fillable = [
        'name',
        'email_configuration_id',
        'subject',
        'body_html',
        'status',
        'audience_type',
        'audience_filters',
        'scheduled_at',
        'started_at',
        'completed_at',
        'total_recipients',
        'sent_count',
        'delivered_count',
        'opened_count',
        'clicked_count',
        'failed_count',
        'throttle_rate',
        'created_by',
    ];

    protected $casts = [
        'audience_filters' => 'array',
        'scheduled_at'     => 'datetime',
        'started_at'       => 'datetime',
        'completed_at'     => 'datetime',
        'total_recipients' => 'integer',
        'sent_count'       => 'integer',
        'delivered_count'  => 'integer',
        'opened_count'     => 'integer',
        'clicked_count'    => 'integer',
        'failed_count'     => 'integer',
        'throttle_rate'    => 'integer',
    ];

    public function recipients(): HasMany
    {
        return $this->hasMany(EmailCampaignRecipient::class, 'campaign_id');
    }

    public function emailConfiguration(): BelongsTo
    {
        return $this->belongsTo(EmailConfiguration::class, 'email_configuration_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->total_recipients <= 0) {
            return ($this->status === 'completed') ? 100 : 0;
        }
        $processed = $this->sent_count + $this->failed_count;
        return (int) min(100, round(($processed / $this->total_recipients) * 100));
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->sent_count <= 0) {
            return 0.0;
        }
        return round(($this->opened_count / $this->sent_count) * 100, 1);
    }

    public function getClickRateAttribute(): float
    {
        if ($this->sent_count <= 0) {
            return 0.0;
        }
        return round(($this->clicked_count / $this->sent_count) * 100, 1);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'completed'  => 'badge-light-success text-success',
            'processing' => 'badge-light-primary text-primary',
            'scheduled'  => 'badge-light-warning text-warning',
            'paused'     => 'badge-light-danger text-danger',
            'cancelled'  => 'badge-light-secondary text-muted',
            default      => 'badge-light-dark text-dark', // draft
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed'  => 'Completed',
            'processing' => 'Sending...',
            'scheduled'  => 'Scheduled',
            'paused'     => 'Paused',
            'cancelled'  => 'Cancelled',
            default      => 'Draft',
        };
    }

    public function getAudienceLabelAttribute(): string
    {
        return match ($this->audience_type) {
            'all_users' => 'All CRM Users',
            'leads'     => 'Leads',
            'orders'    => 'Orders / Clients',
            'writers'   => 'Writers',
            'csv'       => 'CSV Upload',
            default     => ucfirst((string) $this->audience_type),
        };
    }
}
