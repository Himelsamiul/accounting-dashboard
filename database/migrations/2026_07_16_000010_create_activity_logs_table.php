<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable();   // snapshot, survives user deletion
            $table->string('user_role')->nullable();    // snapshot
            $table->string('action')->index();          // login, logout, created, updated, deleted
            $table->string('subject_type')->nullable(); // e.g. Project, Client, Invoice
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_label')->nullable(); // readable identifier
            $table->text('description')->nullable();
            $table->text('changes')->nullable();         // JSON of changed fields (updates)
            $table->string('ip_address', 64)->nullable();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
