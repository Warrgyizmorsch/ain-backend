<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experts extends Model
{
    use HasFactory;

    protected $table = 'expert';
    
    protected $fillable = [
        'name',
        'finish_order',
        'inprogress_order',
        'subject',
        'service',
        'location',
        'content',
        'image',
        'skills',
        'helpus',
        'meta_tag',
        'meta_description',
    ];

    protected $casts = [
        'skills' => 'array',
        'helpus' => 'array',
        'customer_review' => 'array',
    ];
}
