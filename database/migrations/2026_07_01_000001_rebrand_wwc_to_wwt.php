<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * mercalo keeps WWC branding. These migrations are no-ops so they never
 * convert organization / email content from WWC → WWT on this branch.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Intentionally empty on mercalo (WWC).
    }

    public function down(): void
    {
        //
    }
};
