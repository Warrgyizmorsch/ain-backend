<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's default naming convention)
    protected $table = 'faq';

    // Specify the fillable fields for mass assignment
    protected $fillable = ['faqurl_id', 'question', 'answer'];

    // Define relationships (if any, e.g., belongsTo for faqurl_id)
}
