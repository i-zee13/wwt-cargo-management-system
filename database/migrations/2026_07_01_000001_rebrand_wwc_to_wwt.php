<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $wwcToWwt = [
        'WWC' => 'WWT',
        'World Wide Commerce' => 'World Wide Trading Group',
        'Worldwide Commerce' => 'World Wide Trading Group',
        'The WWC Team' => 'The WWT Team',
        'wwc' => 'wwt',
    ];

    public function up(): void
    {
        if (Schema::hasTable('organizations')) {
            $organizations = DB::table('organizations')->get();
            foreach ($organizations as $organization) {
                $updates = [];
                foreach (['name', 'email'] as $column) {
                    if (!isset($organization->{$column}) || $organization->{$column} === null) {
                        continue;
                    }
                    $replaced = $this->replaceWwcStrings($organization->{$column});
                    if ($replaced !== $organization->{$column}) {
                        $updates[$column] = $replaced;
                    }
                }
                if (empty($organization->logo) || str_contains((string) $organization->logo, 'wwc')) {
                    $updates['logo'] = config('brand.default_logo', 'images/wwt-logo.png');
                }
                if ($updates !== []) {
                    DB::table('organizations')->where('id', $organization->id)->update($updates);
                }
            }
        }

        if (Schema::hasTable('email_content_settings')) {
            $settings = DB::table('email_content_settings')->get();
            foreach ($settings as $setting) {
                $updates = [];
                foreach (['subject', 'header_text', 'body_text', 'footer_text'] as $column) {
                    if (!isset($setting->{$column}) || $setting->{$column} === null) {
                        continue;
                    }
                    $replaced = $this->replaceWwcStrings($setting->{$column});
                    if ($replaced !== $setting->{$column}) {
                        $updates[$column] = $replaced;
                    }
                }
                if ($updates !== []) {
                    DB::table('email_content_settings')->where('id', $setting->id)->update($updates);
                }
            }
        }
    }

    public function down(): void
    {
        // Brand migration is intentionally one-way.
    }

    private function replaceWwcStrings(string $value): string
    {
        return str_replace(array_keys($this->wwcToWwt), array_values($this->wwcToWwt), $value);
    }
};
