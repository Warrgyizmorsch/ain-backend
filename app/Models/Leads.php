<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;
    protected $casts = [
    'assign_type' => 'integer',
];
    protected $fillable = [
        'user_name',
        'email',
        'countrycode',
        'mobile',
        'project_title',
        'pages',
        'l_status',
        'deadline',
        'delivery_time',
        'price',
        'message',
        'service_type',
        'tech',
        'resit',
        'order_id',
        'emp_id',
        'create_at',
        'frontendorder',
        'page_url' ,
        'assign_type',
        'lead_source',
        'is_app_lead',
        'subject',
    ];

    public function call()
    {
        return $this->hasMany(Calls::class, 'lead_id', 'id' )->orderBy('created_at', 'desc');
    } 

    public function latestCall()
    {
        return $this->hasOne(Calls::class, 'lead_id', 'id')->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'emp_id');
    }
   
    // public function source()
    // {
    //     return $this->belongsTo(Source::class,'lead_source','id');
    // }

    public function source()
    {
        return $this->belongsTo(Source::class, 'lead_source', 'id');
    }
}
