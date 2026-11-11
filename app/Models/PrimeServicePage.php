<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimeServicePage extends Model
{
    protected $table = 'prime_service_pages';

    protected $fillable = [
        'prime_subject_id',
        'type',
        'slug',
        'meta_title',
        'meta_description',
        'hero_heading',
        'hero_highlight',
        'brand',
        'brand_soft',
        'stats',
        'pricing',
        'hero_content',
        'section_two_heading',
        'section_two_content',
        'section_three_heading',
        'section_three_content',
        'expert_ids',
        'review_ids',
        'faqs',
        'topics',
        'why_heading',
        'why_subheading',
        'why_items',
        'cta_content',
        'cta_button_label',
        'cta_button_url',
        'long_content',
        'is_published',
    ];

    protected $casts = [
        'expert_ids'   => 'array',
        'review_ids'   => 'array',
        'faqs'         => 'array',
        'topics'       => 'array',
        'stats'        => 'array',
        'pricing'      => 'array',
        'why_items'    => 'array',
        'is_published' => 'boolean',
    ];

    public function primeSubject()
    {
        return $this->belongsTo(PrimeSubject::class, 'prime_subject_id');
    }

    /**
     * Alias for view template compatibility with existing service-pages views.
     */
    public function subject()
    {
        return $this->belongsTo(PrimeSubject::class, 'prime_subject_id');
    }

    public function selectedExperts()
    {
        return Experts::whereIn('id', $this->expert_ids ?: [])->get();
    }

    public function selectedReviews()
    {
        return Review::whereIn('id', $this->review_ids ?: [])->get();
    }
}

