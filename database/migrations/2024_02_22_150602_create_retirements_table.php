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
        Schema::create('retirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('exitprocedure_id');
            $table->integer('employee_id');
            $table->date('retirement_date');
            $table->date('notice_date');
            $table->string('retirement_type');
            $table->longtext('description');
            $table->integer('created_by');
            $table->enum('status', ['accepted', 'pending', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retirements');
    }
};
