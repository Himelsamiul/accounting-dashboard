<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_head_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('expense_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
