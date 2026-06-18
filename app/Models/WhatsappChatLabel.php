<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappChatLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'created_by',
    ];
}
