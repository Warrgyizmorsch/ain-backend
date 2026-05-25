<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Sample extends Model
// {
//     use HasFactory;
// }

 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;
    protected $table = 'sample';
    protected $fillable = ['category', 'content', 'title' , 'slug', 'type_id', 'meta_title', 'meta_description']; 

    public function category()
    {
        return $this->hasMany('App\Models\SampleCategory', 'id','category', );              
    }

    public function categotyData()
    {
        return $this->belongsTo('App\Models\SampleCategory', 'category', 'id',);
    }
   
    public function type()
{
    return $this->belongsTo('App\Models\CategoyType', 'type_id', 'id',);
}

}