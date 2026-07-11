<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    /** In-request cache of all settings (key => value). */
    protected static ?array $cache = null;

    protected static function loadAll(): array
    {
        if (static::$cache === null) {
            try {
                static::$cache = static::query()->pluck('value', 'key')->all();
            } catch (\Throwable $e) {
                static::$cache = [];
            }
        }

        return static::$cache;
    }

    public static function get(string $key, $default = null)
    {
        $all = static::loadAll();

        return $all[$key] ?? $default;
    }

    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache = null; // invalidate
    }

    /** Company/app defaults, used when a setting has not been saved yet. */
    public static function defaults(): array
    {
        return [
            'company_name' => 'Prime Byte',
            'company_tagline' => 'Software Solution',
            'company_email' => '',
            'company_phone' => '',
            'company_address' => '',
            'currency_symbol' => '৳',
        ];
    }

    /** All company settings merged with defaults. */
    public static function company(): array
    {
        $out = [];
        foreach (static::defaults() as $key => $default) {
            $val = static::get($key);
            $out[$key] = ($val === null || $val === '') ? $default : $val;
        }

        return $out;
    }

    /** Project-related defaults. */
    public static function projectDefaults(): array
    {
        return [
            'project_code_prefix' => 'PRJ',
            'project_types' => "Software Project\nWebsite\nMobile App\nUI/UX Design\nMaintenance",
            'default_project_value' => '',
        ];
    }

    /** All project settings merged with defaults. */
    public static function project(): array
    {
        $out = [];
        foreach (static::projectDefaults() as $key => $default) {
            $val = static::get($key);
            $out[$key] = ($val === null || $val === '') ? $default : $val;
        }

        return $out;
    }

    /** Project types as a clean array (one per line in the setting). */
    public static function projectTypes(): array
    {
        $raw = static::project()['project_types'];

        return collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();
    }
}
