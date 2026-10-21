<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailCampaignRecipient extends Model
{
    use HasFactory;

    protected $table = 'email_campaign_recipients';

    protected $fillable = [
        'campaign_id',
        'recipient_email',
        'recipient_name',
        'user_id',
        'lead_id',
        'status',
        'tracking_token',
        'sent_at',
        'opened_at',
        'clicked_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'opened_at'  => 'datetime',
        'clicked_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'sent'    => 'badge-light-success text-success',
            'failed'  => 'badge-light-danger text-danger',
            'bounced' => 'badge-light-warning text-warning',
            default   => 'badge-light-primary text-primary', // pending
        };
    }
}
