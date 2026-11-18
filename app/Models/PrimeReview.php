<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimeReview extends Model
{
    protected $table = 'prime_reviews';

    protected $fillable = [
        'name',
        'page',
        'university',
        'course',
        'deadline',
        'submission_date',
        'services_type',
        'location',
        'customer_rating',
        'description',
        'review_reply',
        'is_active',
    ];

    protected $casts = [
        'submission_date' => 'date:Y-m-d',
        'customer_rating' => 'integer',
        'is_active' => 'boolean',
    ];
}
