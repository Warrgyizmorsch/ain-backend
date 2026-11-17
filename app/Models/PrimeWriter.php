<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeWriter extends Model
{
    use HasFactory;

    protected $table = 'prime_writers';

    protected $fillable = [
        'name',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
