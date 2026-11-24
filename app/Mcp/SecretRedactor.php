<?php

namespace App\Mcp;

class SecretRedactor
{
    public static function clean(string $text, ?string $secret = null): string
    {
        if ($secret !== null && $secret !== '') {
            $text = str_replace($secret, '[REDACTED]', $text);
        }

        return preg_replace('/Bearer\s+\S+/i', 'Bearer [REDACTED]', $text) ?? $text;
    }
}
