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
        Schema::create('employee_onboarding_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('branch');
            $table->integer('department')->nullable();
            $table->string('header_title');
            $table->string('header_option');
            $table->string('video_url')->nullable();
            $table->string('video_file_path')->nullable();
            $table->string('image_file_path')->nullable();
            $table->text('header_description');
            $table->boolean('attachments_status')->default(true);
            $table->integer('created_by');
            $table->timestamps();
        });

        Schema::create('employee_onboarding_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_onboarding_template_id');
            $table->string('uuid');
            $table->string('name');
            $table->enum('type', ['text', 'textarea', 'radio', 'file']);
            $table->integer('word_count')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_onboarding_files', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_onboarding_template_id');
            $table->string('uuid');
            $table->enum('file_type', ['read_and_approve', 'read']);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_onboarding_files');
        Schema::dropIfExists('employee_onboarding_questions');
        Schema::dropIfExists('employee_onboarding_templates');
    }
};
