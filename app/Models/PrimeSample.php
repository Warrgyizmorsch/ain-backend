<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeSample extends Model
{
    use HasFactory;

    protected $table = 'prime_samples';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'pages_count',
        'words_count',
        'is_published',
    ];

    protected $casts = [
        'pages_count'  => 'integer',
        'words_count'  => 'integer',
        'is_published' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(PrimeSampleCategory::class, 'category_id');
    }
}

