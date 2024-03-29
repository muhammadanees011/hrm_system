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
        Schema::create('employee_onboarding_file_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_onboarding_template_id');
            $table->integer('employee_onboarding_file_id');
            $table->integer('job_application_id');
            $table->boolean('approve_status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_onboarding_file_approvals');
    }
};
