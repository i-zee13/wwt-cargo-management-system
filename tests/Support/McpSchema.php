<?php

namespace Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class McpSchema
{
    public static function create(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->unsignedInteger('designation')->nullable();
            $table->unsignedTinyInteger('super')->default(0);
            $table->string('password_changed')->nullable();
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('suite')->nullable();
            $table->string('phone')->nullable();
            $table->string('client_type')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
        });

        Schema::create('origins', function (Blueprint $table) {
            $table->id();
            $table->string('origin_name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('iso')->nullable();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        Schema::create('freight_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('origin_id')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->decimal('additional', 10, 2)->nullable();
            $table->string('type')->nullable();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('waybill')->nullable();
            $table->string('original_tracking')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_suite')->nullable();
            $table->string('client_name')->nullable();
            $table->unsignedBigInteger('origin_id')->nullable();
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->text('comments')->nullable();
            $table->string('is_insured')->nullable();
            $table->decimal('kg', 10, 2)->nullable();
            $table->decimal('cbm', 10, 3)->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamp('status_change_date')->nullable();
            $table->unsignedTinyInteger('payment_status')->nullable();
            $table->timestamps();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('facebook_link')->nullable();
        });

        Schema::create('organization_phone_no', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('phone_number')->nullable();
        });

        Schema::create('controllers', function (Blueprint $table) {
            $table->id();
            $table->string('controller')->nullable();
        });

        Schema::create('controller_designation_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('designation_id')->nullable();
            $table->string('controller_name')->nullable();
        });

        Schema::create('mcp_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_hash', 64)->unique();
            $table->string('label')->nullable();
            $table->string('actor_type', 20);
            $table->unsignedBigInteger('actor_id');
            $table->string('aide_workspace_id');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('mcp_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->string('workspace_id')->nullable();
            $table->string('actor_type', 20)->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('method')->nullable();
            $table->string('tool')->nullable();
            $table->boolean('ok')->default(false);
            $table->string('error_code')->nullable();
            $table->timestamps();
        });
    }
}
