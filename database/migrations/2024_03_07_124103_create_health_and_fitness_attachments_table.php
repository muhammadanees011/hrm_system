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
        Schema::create('health_and_fitness_attachments', function (Blueprint $table) {        
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('healthassessment_id')->nullable();
            $table->unsignedBigInteger('gpnotes_id')->nullable();
            $table->unsignedBigInteger('selfcertification_id')->nullable();
            $table->string('files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_and_fitness_attachments');
    }
};
