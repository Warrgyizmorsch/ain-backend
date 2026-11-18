<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimeExpert extends Model
{
    protected $table = 'prime_experts';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'image_alt',
        'gender',
        'degree',
        'experience',
        'finish_order',
        'inprogress_order',
        'success',
        'rating',
        'subject',
        'service',
        'location',
        'content',
        'skills',
        'helpus',
        'customer_review',
        'meta_tag',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'helpus' => 'array',
        'customer_review' => 'array',
        'is_active' => 'boolean',
        'rating' => 'float',
    ];
}
