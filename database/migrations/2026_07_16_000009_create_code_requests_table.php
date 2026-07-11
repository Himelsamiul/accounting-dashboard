<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('code_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note')->nullable();
            $table->string('status')->default('pending'); // pending | sent
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('code_requests');
    }
};
