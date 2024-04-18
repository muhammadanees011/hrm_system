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
        Schema::create('employee_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('performancecycle_id');
            $table->text('title');
            $table->integer('total_reviews')->default(0);
            $table->integer('completed_reviews')->default(0);
            $table->string('who_can_review')->nullable();
            $table->string('who_to_review')->nullable();
            $table->enum('status',['Completed','Pending','Running'])->default('Running');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_reviews');
    }
};
