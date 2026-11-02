<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeSampleCategory extends Model
{
    use HasFactory;

    protected $table = 'prime_sample_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function samples()
    {
        return $this->hasMany(PrimeSample::class, 'category_id');
    }
}

