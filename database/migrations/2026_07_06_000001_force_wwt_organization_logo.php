<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organizations')) {
            return;
        }

        $defaultName = config('brand.name', 'World Wide Trading Group');
        $organizations = DB::table('organizations')->get();

        foreach ($organizations as $organization) {
            $name = $organization->name ?? $defaultName;
            $name = str_replace(
                ['WWC', 'World Wide Commerce', 'Worldwide Commerce', 'WORLDWIDECOMMERCE'],
                ['WWT', 'World Wide Trading Group', 'World Wide Trading Group', 'WORLD WIDE TRADING'],
                $name
            );

            if ($name !== $organization->name) {
                DB::table('organizations')->where('id', $organization->id)->update([
                    'name' => $name,
                ]);
            }
        }
    }

    public function down(): void
    {
        // One-way branding migration.
    }
};
