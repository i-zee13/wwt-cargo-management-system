<?php

use Illuminate\Database\Migrations\Migration;

/**
 * mercalo keeps WWC (or legacy COMM) suite prefixes — do not convert COMM → WWT.
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
