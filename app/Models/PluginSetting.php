<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PluginSetting extends Model
{
    use HasFactory;

    protected $table = 'plugin_settings';

    protected $fillable = [
        'plugin_key',
        'name',
        'category',
        'description',
        'is_active',
        'settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get settings value by key with optional fallback.
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings, $key, $default);
    }
}
