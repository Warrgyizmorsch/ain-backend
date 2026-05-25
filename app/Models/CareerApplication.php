<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/CareerApplication.php
class CareerApplication extends Model
{
    protected $fillable = [
    'name',
    'email',
    'phone',
    'current_location',
    'experience_years',
    'role_mode',
    'role_title',

    // 👉 new by ansh:
    'current_ctc',
    'expected_ctc',
    'notice_period',

    'message',
    'resume_url',
    'portfolio_url',
    'source',
    'status',
];

}

