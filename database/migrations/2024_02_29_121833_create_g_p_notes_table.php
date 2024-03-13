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
        Schema::create('g_p_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id');
            $table->date('assessment_date');
            $table->string('presenting_complaint');
            $table->string('history_of_present_illness')->nullable();
            $table->string('past_medical_history')->nullable();
            $table->string('assessment')->nullable();
            $table->longText('plan')->nullable();
            $table->string('additional_comments')->nullable();
            $table->string('prescription_file')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_p_notes');
    }
};
