<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappChatState extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'provider',
        'conversation_status',
        'provider_updated_at',
    ];

    protected $casts = [
        'provider_updated_at' => 'datetime',
    ];
}
