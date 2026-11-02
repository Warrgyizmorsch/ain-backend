<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimeSubject extends Model
{
    protected $table = 'prime_subjects';

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function servicePages()
    {
        return $this->hasMany(PrimeServicePage::class, 'prime_subject_id');
    }

    public function primeServicePages()
    {
        return $this->hasMany(PrimeServicePage::class, 'prime_subject_id');
    }
}

