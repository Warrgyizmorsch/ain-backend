<?php

 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqUrl extends Model
{
    
    use HasFactory;
    protected $table = 'faqurl'; 

    public function faqs()
    {
        // Define the one-to-many relationship
        return $this->hasMany(Faq::class, 'faqurl_id', 'id');
    }

}