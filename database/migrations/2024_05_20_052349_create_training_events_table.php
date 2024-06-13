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
        Schema::create('training_events', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->longText('department_id');
            $table->longText('employee_id');
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('color');
            $table->text('description')->nullable();
            $table->integer('participants_limit')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_events');
    }
};
