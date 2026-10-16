<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeerContactLog extends Model
{
    use HasFactory;

    protected $table = 'peer_contact_logs';

    protected $fillable = [
        'peer_identifier',
        'peer_user_id',
        'peer_phone',
        'order_id',
        'contacted_by',
        'contacted_at',
        'is_contacted',
        'notes',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
        'is_contacted' => 'boolean',
    ];

    public function contactedByUser()
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
