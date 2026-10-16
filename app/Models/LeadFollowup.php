<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    protected $table = 'lead_followups';

    protected $fillable = [
        'lead_id',
        'lead_type',
        'user_id',
        'message',
        'followup_date',
        'followup_time',
        'status',
        'reminded_at',
        'snoozed_until',
        'done_by',
        'done_at',
    ];

    protected $casts = [
        'followup_date' => 'date',
        'reminded_at' => 'datetime',
        'snoozed_until' => 'datetime',
        'done_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doneByUser()
    {
        return $this->belongsTo(User::class, 'done_by');
    }

    public function lead()
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }
}
