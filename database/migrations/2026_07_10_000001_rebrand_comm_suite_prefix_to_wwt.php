<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rebrand client suite prefix COMM → WWT (and keep packages.client_suite in sync).
     */
    public function up(): void
    {
        $prefix = config('brand.suite_prefix', 'WWT');

        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'suite')) {
            DB::table('clients')
                ->where('suite', 'like', 'COMM%')
                ->orderBy('id')
                ->chunkById(200, function ($clients) use ($prefix) {
                    foreach ($clients as $client) {
                        $newSuite = $prefix.substr($client->suite, 4);
                        DB::table('clients')->where('id', $client->id)->update(['suite' => $newSuite]);
                    }
                });
        }

        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'client_suite')) {
            DB::table('packages')
                ->where('client_suite', 'like', 'COMM%')
                ->orderBy('id')
                ->chunkById(200, function ($packages) use ($prefix) {
                    foreach ($packages as $package) {
                        $newSuite = $prefix.substr($package->client_suite, 4);
                        DB::table('packages')->where('id', $package->id)->update(['client_suite' => $newSuite]);
                    }
                });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'suite')) {
            DB::table('clients')
                ->where('suite', 'like', 'WWT%')
                ->orderBy('id')
                ->chunkById(200, function ($clients) {
                    foreach ($clients as $client) {
                        $oldSuite = 'COMM'.substr($client->suite, 3);
                        DB::table('clients')->where('id', $client->id)->update(['suite' => $oldSuite]);
                    }
                });
        }

        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'client_suite')) {
            DB::table('packages')
                ->where('client_suite', 'like', 'WWT%')
                ->orderBy('id')
                ->chunkById(200, function ($packages) {
                    foreach ($packages as $package) {
                        $oldSuite = 'COMM'.substr($package->client_suite, 3);
                        DB::table('packages')->where('id', $package->id)->update(['client_suite' => $oldSuite]);
                    }
                });
        }
    }
};
