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
        Schema::create('leave_entitlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('base_allowance')->nullable();
            $table->integer('carry_over')->nullable();
            $table->integer('total_allowance')->nullable();
            $table->integer('absence_count')->nullable();
            $table->integer('remaining_allowance')->nullable();
            $table->integer('holidays_taken')->nullable();
            $table->integer('maternity_paternity')->nullable();
            $table->integer('sick_leaves_taken')->nullable();
            $table->date('employee_start_date')->nullable();
            $table->date('employee_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_entitlements');
    }
};
