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
        Schema::create('payroll_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('income_tax_percentage')->nullable();
            $table->integer('late_arrival_or_early_departure_threshold')->nullable();
            $table->integer('late_arrival_or_early_departure_amount')->nullable();
            $table->integer('provident_funds_deduction_percentage')->nullable();
            $table->enum('salary_calculation_method', ['Hourly', 'Fixed'])->default('Fixed');
            $table->enum('pay_frequency', ['Monthly', 'Weekly'])->default('Monthly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_setups');
    }
};
