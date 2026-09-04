<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EmailMessage extends Model
{
    use HasFactory;

    protected $table = 'email_messages';

    protected $fillable = [
        'message_id',
        'email_configuration_id',
        'in_reply_to',
        'references',
        'thread_id',
        'from_email',
        'from_name',
        'to_email',
        'to_name',
        'cc',
        'bcc',
        'reply_to',
        'subject',
        'body_html',
        'body_plain',
        'folder',
        'direction',
        'status',
        'is_read',
        'is_starred',
        'is_draft',
        'has_attachments',
        'received_at',
        'sent_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_draft' => 'boolean',
        'has_attachments' => 'boolean',
        'received_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EmailMessage $msg) {
            if (empty($msg->thread_id)) {
                $msg->thread_id = (string) Str::uuid();
            }
            if (empty($msg->message_id)) {
                $msg->message_id = '<' . Str::random(24) . '@' . (request()->getHost() ?? 'ain-backend.com') . '>';
            }
        });
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class, 'email_message_id');
    }

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(EmailConfiguration::class, 'email_configuration_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_email', 'email');
    }

    // Scopes
    public function scopeInbox($query)
    {
        return $query->where('folder', 'inbox')->where('is_draft', false);
    }

    public function scopeSent($query)
    {
        return $query->where('folder', 'sent')->where('is_draft', false);
    }

    public function scopeDrafts($query)
    {
        return $query->where('folder', 'drafts')->orWhere('is_draft', true);
    }

    public function scopeTrash($query)
    {
        return $query->where('folder', 'trash');
    }

    public function scopeStarred($query)
    {
        return $query->where('is_starred', true)->where('folder', '!=', 'trash');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function getSnippetAttribute(): string
    {
        $text = strip_tags($this->body_plain ?: $this->body_html ?: '');
        return Str::limit(trim(preg_replace('/\s+/', ' ', $text)), 120);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->direction === 'outbound') {
            return $this->to_name ?: $this->to_email ?: 'Recipient';
        }
        return $this->from_name ?: $this->from_email ?: 'Sender';
    }

    public function getCustomerEmailAttribute(): ?string
    {
        $raw = ($this->direction === 'outbound' || $this->folder === 'sent' || $this->folder === 'drafts')
            ? ($this->to_email ?: $this->from_email)
            : ($this->from_email ?: $this->to_email);

        return self::extractCleanEmail($raw);
    }

    public static function extractCleanEmail(?string $emailStr): ?string
    {
        if (empty($emailStr)) return null;
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $emailStr, $matches)) {
            return strtolower(trim($matches[0]));
        }
        return strtolower(trim($emailStr));
    }
}

