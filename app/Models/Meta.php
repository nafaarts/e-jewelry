<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $meta = self::where('key', $key)->first();
        return $meta ? $meta->value : $default;
    }

    public static function set($key, $value)
    {
        $meta = self::firstOrNew(['key' => $key]);
        $meta->value = $value;
        $meta->save();
    }

    public static function forget($key)
    {
        self::where('key', $key)->delete();
    }

    public static function has($key)
    {
        return self::where('key', $key)->exists();
    }
}
