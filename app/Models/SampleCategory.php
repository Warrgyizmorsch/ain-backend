<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleCategory extends Model
{
    use HasFactory;
    protected $table = 'samplecategory';
    protected $fillable = ['name']; 

    public function Sample()
    {
        return $this->hasMany('App\Models\Sample', 'category', 'id');              
    }

    

}
