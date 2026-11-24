<?php

namespace App\Mcp;

use Illuminate\Support\Facades\File;

class McpFeature
{
    public static function isEnabled(): bool
    {
        if (! filter_var(config('mcp.enabled'), FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        return self::uiEnabled();
    }

    public static function envEnabled(): bool
    {
        return filter_var(config('mcp.enabled'), FILTER_VALIDATE_BOOLEAN);
    }

    public static function uiEnabled(): bool
    {
        $path = self::uiStatePath();
        if (! File::exists($path)) {
            return true;
        }

        $raw = trim((string) File::get($path));

        return filter_var($raw, FILTER_VALIDATE_BOOLEAN);
    }

    public static function setUiEnabled(bool $enabled): void
    {
        $path = self::uiStatePath();
        $directory = dirname($path);
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        File::put($path, $enabled ? '1' : '0');
    }

    public static function status(): array
    {
        return [
            'env_enabled' => self::envEnabled(),
            'ui_enabled' => self::uiEnabled(),
            'active' => self::isEnabled(),
        ];
    }

    private static function uiStatePath(): string
    {
        return storage_path('app/mcp/ui-enabled');
    }
}
