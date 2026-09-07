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
        'is_whatsapp',
        'is_email',
        'is_crm',
        'sequence',
        'created_by',
    ];

    protected $casts = [
        'is_whatsapp' => 'boolean',
        'is_email' => 'boolean',
        'is_crm' => 'boolean',
        'sequence' => 'integer',
    ];

    /**
     * Scope to order labels by sequence first, then ID
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Scope for WhatsApp channel labels
     */
    public function scopeForWhatsapp($query)
    {
        return $query->where('is_whatsapp', true);
    }

    /**
     * Scope for Email channel labels
     */
    public function scopeForEmail($query)
    {
        return $query->where('is_email', true);
    }

    /**
     * Scope for CRM / Orders channel labels
     */
    public function scopeForCrm($query)
    {
        return $query->where('is_crm', true);
    }
}
