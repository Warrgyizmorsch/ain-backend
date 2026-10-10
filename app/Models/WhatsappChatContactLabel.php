<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappChatContactLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'label_id',
        'assigned_by',
    ];

    public function label()
    {
        return $this->belongsTo(WhatsappChatLabel::class, 'label_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
