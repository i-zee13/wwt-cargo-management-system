<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        if (! Schema::hasColumn('clients', 'remember_token')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->rememberToken()->nullable()->after('password');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'remember_token')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
