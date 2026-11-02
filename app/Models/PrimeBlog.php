<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeBlog extends Model
{
    use HasFactory;

    protected $table = 'prime_blogs';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'author_id',
        'author_name',
        'category',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'faqs',
        'is_published',
    ];

    protected $casts = [
        'faqs'         => 'array',
        'is_published' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('assets/media/avatars/demo.png');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return url($this->image);
    }
}

