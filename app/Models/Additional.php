<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    protected $table = 'additional';

    protected $fillable = [
        'order_id',
        'additional_word_count',
        'additional_price',
    ];
}
