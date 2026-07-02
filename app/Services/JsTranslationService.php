<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class JsTranslationService
{
    public function getJavaScript(): string
    {
        $cacheKey = config('translation.cache_key', 'js_translation_messages');
        $ttl = config('translation.cache_ttl');

        if ($ttl === null) {
            return Cache::rememberForever($cacheKey, fn () => $this->buildJavaScript());
        }

        return Cache::remember($cacheKey, $ttl, fn () => $this->buildJavaScript());
    }

    public function clearCache(): void
    {
        Cache::forget(config('translation.cache_key', 'js_translation_messages'));
    }

    public function buildJavaScript(): string
    {
        $messages = $this->collectMessages();
        $json = json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $core = File::get(public_path('vendor/langjs/lang-core.js'));

        return $core . "\n\n(function () {\n"
            . "    Lang = new Lang();\n"
            . "    Lang.setMessages({$json});\n"
            . "})();\n";
    }

    protected function collectMessages(): array
    {
        $messages = [];
        $groups = config('translation.js_groups', ['fields']);
        $locales = config('translation.supported_locales', ['en', 'es']);

        foreach ($locales as $locale) {
            $localePath = resource_path("lang/{$locale}");

            if (! is_dir($localePath)) {
                continue;
            }

            foreach ($groups as $group) {
                $file = "{$localePath}/{$group}.php";

                if (! is_file($file)) {
                    continue;
                }

                $lines = include $file;

                if (is_array($lines)) {
                    $messages["{$locale}.{$group}"] = $lines;
                }
            }
        }

        return $messages;
    }
}
