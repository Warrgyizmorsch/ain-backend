<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Spreadsheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spreadsheets';

    protected $fillable = [
        'uuid',
        'title',
        'sheet_data',
        'created_by',
        'updated_by',
        'is_favorite',
        'is_archived',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'is_archived' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($spreadsheet) {
            if (empty($spreadsheet->uuid)) {
                $spreadsheet->uuid = (string) Str::uuid();
            }
            if (empty($spreadsheet->title)) {
                $spreadsheet->title = 'Untitled Spreadsheet';
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
