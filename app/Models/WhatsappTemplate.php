<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'category',
        'language',
        'header_type',
        'header_text',
        'body',
        'footer_text',
        'buttons',
        'variables',
        'status',
        'is_active',
    ];

    protected $casts = [
        'buttons' => 'array',
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'APPROVED');
    }
}
