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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('civil_status');
            $table->string('nationality');
            $table->string('present_address');
            $table->string('permanent_address');
            $table->string('contact_number');
            $table->string('email');
            
            // Employment Information
            $table->string('employment_status');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_phone');
            $table->string('position');
            $table->decimal('monthly_income', 12, 2);
            $table->integer('years_employed');
            
            // Identification
            $table->string('valid_id_type');
            $table->string('valid_id_number');
            $table->string('valid_id_front_path');
            $table->string('valid_id_back_path');
            
            // Additional Documents
            $table->string('proof_of_income_path');
            $table->string('proof_of_billing_path');
            
            // Status
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
