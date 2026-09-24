<?php

use Illuminate\Database\Migrations\Migration;

/**
 * mercalo keeps WWC / World Wide Commerce organization naming.
 * No-op so organization names are not forced to WWT on this branch.
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
