<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'message',
        'direction',
        'wa_message_id',
        'status',
        'media_url',
        'media_type',
        'media_name',
        'media_size',
    ];

    protected static function booted(): void
    {
        static::creating(function (WhatsappMessage $message) {
            if (empty($message->wa_message_id)) {
                $message->wa_message_id = 'wa_' . (string) Str::uuid();
            }
            if (empty($message->name)) {
                $message->name = 'User';
            }
        });
    }
}
