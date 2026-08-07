<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextLead extends Model
{
    use HasFactory;

    protected $table = 'next_leads';

    protected $fillable = [
        'user_name',
        'countrycode',
        'mobile',
        'email',
        'emp_id',
        'target_month',
        'message',
        'created_by',
        'is_converted',
        'converted_lead_id',
        'converted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'emp_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function convertedLead()
    {
        return $this->belongsTo(Leads::class, 'converted_lead_id');
    }
}
