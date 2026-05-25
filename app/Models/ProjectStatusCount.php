<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatusCount extends Model
{
    use HasFactory;
    protected $table = 'project_status_counts';
    protected $fillable = ['order_Id', 'status', 'count'];
}
