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
        Schema::create('salary_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salaries_id')->constrained('salaries')->onDelete('cascade');
            $table->foreignId('employees_id')->constrained('employees')->onDelete('cascade');
            $table->date('claim_date');
            $table->enum('status', ['pending','completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_claims');
    }
};
