<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model {
    protected $fillable = ['key','value','group','type','description'];

    public static function get(string $key, $default = null) {
        $setting = static::where('key',$key)->first();
        if (!$setting) return $default;
        return match($setting->type) {
            'boolean' => $setting->value === 'true',
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }
    public static function set(string $key, $value): void {
        static::where('key',$key)->update(['value'=>is_array($value)?json_encode($value):(string)$value]);
    }
}
