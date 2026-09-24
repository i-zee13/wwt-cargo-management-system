<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Restore WWC branding on mercalo if data was previously converted to WWT.
 */
return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('brand.suite_prefix', 'WWC');

        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'suite')) {
            DB::table('clients')
                ->where('suite', 'like', 'WWT%')
                ->orderBy('id')
                ->chunkById(200, function ($clients) use ($prefix) {
                    foreach ($clients as $client) {
                        $newSuite = $prefix.substr($client->suite, 3);
                        DB::table('clients')->where('id', $client->id)->update(['suite' => $newSuite]);
                    }
                });
        }

        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'client_suite')) {
            DB::table('packages')
                ->where('client_suite', 'like', 'WWT%')
                ->orderBy('id')
                ->chunkById(200, function ($packages) use ($prefix) {
                    foreach ($packages as $package) {
                        $newSuite = $prefix.substr($package->client_suite, 3);
                        DB::table('packages')->where('id', $package->id)->update(['client_suite' => $newSuite]);
                    }
                });
        }

        if (Schema::hasTable('organizations')) {
            $organizations = DB::table('organizations')->get();
            foreach ($organizations as $organization) {
                $name = $organization->name ?? '';
                $replaced = str_replace(
                    ['World Wide Trading Group', 'WORLD WIDE TRADING', 'WWT'],
                    ['World Wide Commerce', 'WORLD WIDE COMMERCE', 'WWC'],
                    $name
                );
                if ($replaced !== $name) {
                    DB::table('organizations')->where('id', $organization->id)->update(['name' => $replaced]);
                }
            }
        }

        if (Schema::hasTable('email_content_settings')) {
            $replacements = [
                'World Wide Trading Group' => 'World Wide Commerce',
                'The WWT Team' => 'The WWC Team',
                'WWT' => 'WWC',
                'wwt' => 'wwc',
            ];
            $settings = DB::table('email_content_settings')->get();
            foreach ($settings as $setting) {
                $updates = [];
                foreach (['subject', 'header_text', 'body_text', 'footer_text'] as $column) {
                    if (! isset($setting->{$column}) || $setting->{$column} === null) {
                        continue;
                    }
                    $replaced = str_replace(array_keys($replacements), array_values($replacements), $setting->{$column});
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
        // One-way restore for mercalo WWC branding.
    }
};
