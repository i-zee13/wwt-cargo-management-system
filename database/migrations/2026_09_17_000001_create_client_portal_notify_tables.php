<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portal_notify_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('subject');
            $table->text('body_template');
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('sent')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->string('status', 32)->default('pending'); // pending|processing|completed|failed
            $table->timestamps();
        });

        Schema::create('client_portal_notify_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('client_id');
            $table->string('email');
            $table->string('status', 32)->default('pending'); // pending|sent|failed
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('batch_id')
                ->references('id')
                ->on('client_portal_notify_batches')
                ->onDelete('cascade');
            $table->index(['status', 'batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_notify_items');
        Schema::dropIfExists('client_portal_notify_batches');
    }
};
