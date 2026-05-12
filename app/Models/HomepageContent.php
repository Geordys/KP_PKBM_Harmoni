<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'key',
        'label',
        'value',
        'type',
    ];

    /**
     * Get content by key or return default.
     */
    public static function getContent($key, $default = '')
    {
        $item = self::where('key', $key)->first();
        return $item ? $item->value : $default;
    }
}
