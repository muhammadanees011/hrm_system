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
        Schema::create('employee_onboarding_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_onboarding_template_id');
            $table->integer('employee_onboarding_question_id');
            $table->integer('job_application_id');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_onboarding_answers');
    }
};
