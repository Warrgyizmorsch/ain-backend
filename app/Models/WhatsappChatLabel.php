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
        'is_client_email',
        'is_writer_email',
        'is_email',
        'is_crm',
        'sequence',
        'created_by',
    ];

    protected $casts = [
        'is_whatsapp' => 'boolean',
        'is_client_email' => 'boolean',
        'is_writer_email' => 'boolean',
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
     * Scope for Client Email channel labels
     */
    public function scopeForClientEmail($query)
    {
        return $query->where('is_client_email', true);
    }

    /**
     * Scope for Writer Email channel labels
     */
    public function scopeForWriterEmail($query)
    {
        return $query->where('is_writer_email', true);
    }

    /**
     * Scope for specific Email Account (Client vs Writer)
     * If account is Writer: only is_writer_email
     * If account is Client: only is_client_email
     * Fallback: any email channel
     */
    public function scopeForEmailAccount($query, $account = null)
    {
        if ($account) {
            $name = is_object($account) ? ($account->name ?? '') : (string) $account;
            $id = is_object($account) ? ($account->id ?? 0) : (is_numeric($account) ? (int) $account : 0);

            if ((int) $id === 1 || stripos($name, 'writer') !== false) {
                return $query->where('is_writer_email', true);
            }

            if ((int) $id === 2 || stripos($name, 'client') !== false) {
                return $query->where('is_client_email', true);
            }
        }

        return $query->where(function ($q) {
            $q->where('is_client_email', true)
              ->orWhere('is_writer_email', true)
              ->orWhere('is_email', true);
        });
    }

    /**
     * Scope for Email channel labels
     */
    public function scopeForEmail($query)
    {
        return $query->where(function ($q) {
            $q->where('is_email', true)
              ->orWhere('is_client_email', true)
              ->orWhere('is_writer_email', true);
        });
    }

    /**
     * Scope for CRM / Orders channel labels
     */
    public function scopeForCrm($query)
    {
        return $query->where('is_crm', true);
    }
}

