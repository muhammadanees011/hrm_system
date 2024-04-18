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
        Schema::create('goal_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('goal_id');
            $table->text('title');
            $table->integer('progress')->default(0);
            $table->enum('status',['Achieved','Pending','In Progress'])->default('Pending');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_results');
    }
};
