<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeSampleType extends Model
{
    use HasFactory;

    protected $table = 'prime_sample_types';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function samples()
    {
        return $this->hasMany(PrimeSample::class, 'type_id');
    }
}
