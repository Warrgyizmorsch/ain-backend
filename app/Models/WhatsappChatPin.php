<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappChatPin extends Model
{
    protected $table = 'whatsapp_chat_pins';

    protected $fillable = [
        'phone',
        'user_id',
    ];
}
