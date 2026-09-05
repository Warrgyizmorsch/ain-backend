<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappChatArchive extends Model
{
    protected $table = 'whatsapp_chat_archives';

    protected $fillable = [
        'phone',
    ];
}
