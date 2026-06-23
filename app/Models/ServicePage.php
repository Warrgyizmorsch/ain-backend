<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePage extends Model
{
    protected $fillable = [
        'subject_id', 'slug', 'meta_title', 'meta_description', 'hero_heading',
        'hero_highlight', 'hero_content', 'section_two_heading', 'section_two_content',
        'section_three_heading', 'section_three_content', 'expert_ids', 'review_ids',
        'faqs', 'why_heading', 'why_subheading', 'why_items', 'cta_content',
        'cta_button_label', 'cta_button_url', 'is_published',
    ];

    protected $casts = [
        'expert_ids' => 'array',
        'review_ids' => 'array',
        'faqs' => 'array',
        'why_items' => 'array',
        'is_published' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
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
