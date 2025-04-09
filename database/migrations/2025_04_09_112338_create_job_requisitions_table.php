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
        Schema::create('job_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
            $table->date('request_date')->nullable();
            $table->string('job_title')->nullable();
            $table->integer('no_of_positions');
            $table->enum('position_type',['new','replacement']);
            $table->string('previous_employee')->nullable();
            $table->string('work_location')->nullable();
            $table->enum('remote_work',['yes','no']);
            $table->string('work_Schedule')->nullable();
            $table->date('start_date')->nullable();
            $table->enum('employement_type',['Full-time','Part-time','Contract','Temporary']);
            $table->integer('positions')->default(1);
            $table->integer('experience_required')->default(0);
            $table->double('salary_range')->default(0);
            $table->string('job_grade')->nullable();
            $table->string('budget_code')->nullable();
            $table->enum('budgeted',['yes','no']);
            $table->string('hiring_manager')->nullable();
            $table->string('hr_bussiness_partner')->nullable();
            $table->string('budget_approval')->nullable();
            $table->string('executive_approval')->nullable();
            $table->text('comments')->nullable();
            $table->text('position_summary')->nullable();
            $table->text('key_responsibilities')->nullable();
            $table->text('required_qualifications')->nullable();
            $table->text('preferred_qualifications')->nullable();
            $table->enum('status',['Pending','Approved','Rejected']);
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requisitions');
    }
};
