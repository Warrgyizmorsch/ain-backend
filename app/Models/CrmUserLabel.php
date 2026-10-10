<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmUserLabel extends Model
{
    use HasFactory;

    protected $table = 'crm_user_labels';

    protected $fillable = [
        'user_id',
        'phone',
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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
