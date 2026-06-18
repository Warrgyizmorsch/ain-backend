<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'message', 'direction', 'wa_message_id', 'status', 'media_url', 'media_type', 'media_name', 'media_size'];
}
