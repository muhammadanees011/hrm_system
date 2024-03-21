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
        Schema::create('job_template_details', function (Blueprint $table) {
            $table->id();
            $table->integer('job_template_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('requirement')->nullable();
            $table->integer('branch')->default(0);
            $table->integer('department')->nullable()->after('branch');
            $table->string('contract_type')->nullable()->after('department');
            $table->integer('category')->default(0);
            $table->text('skill')->nullable();
            $table->integer('position')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->nullable();
            $table->string('applicant')->nullable();
            $table->string('visibility')->nullable();
            $table->string('code')->nullable();
            $table->string('custom_question')->nullable();
            $table->integer('question_template_id')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_template_details');
    }
};
