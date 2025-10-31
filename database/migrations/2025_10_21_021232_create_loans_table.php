<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('purpose')->nullable();
            $table->integer('term')->default(0);
            $table->decimal('interest_rate', 5, 2)->default(5.00);
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->decimal('monthly_payment', 12, 2);
            $table->decimal('remaining_balance', 12, 2);
            $table->date('next_due_date')->nullable();
            $table->integer('months_paid')->nullable();
            $table->integer('months_left')->nullable();
            $table->decimal('principal_amount', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
