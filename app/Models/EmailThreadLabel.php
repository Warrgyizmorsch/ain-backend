<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailThreadLabel extends Model
{
    use HasFactory;

    protected $table = 'email_thread_labels';

    protected $fillable = [
        'thread_id',
        'email',
        'label_id',
        'assigned_by',
    ];

    public function label(): BelongsTo
    {
        return $this->belongsTo(WhatsappChatLabel::class, 'label_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
