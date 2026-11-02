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
            $default = 'assets/media/avatars/demo.png';
            return self::makeHttpsUrl(asset($default));
        }

        $imagePath = trim($this->image);

        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return self::makeHttpsUrl($imagePath);
        }

        $cleanPath = ltrim($imagePath, '/\\');
        return self::makeHttpsUrl(asset($cleanPath));
    }

    protected static function makeHttpsUrl(string $url): string
    {
        $isHttps = false;
        try {
            if (function_exists('request') && request()) {
                $isHttps = request()->isSecure()
                    || str_contains(request()->header('x-forwarded-proto', ''), 'https')
                    || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    || str_contains(url()->current(), 'https://');
            }
        } catch (\Throwable $e) {
            $isHttps = false;
        }

        if ($isHttps && str_starts_with($url, 'http://')) {
            return 'https://' . substr($url, 7);
        }

        return $url;
    }
}
