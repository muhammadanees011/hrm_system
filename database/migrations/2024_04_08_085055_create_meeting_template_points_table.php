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
        Schema::create('meeting_template_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_template_id');
            $table->foreign('meeting_template_id')->references('id')->on('meeting_templates')->onDelete('cascade');
            $table->text('title');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_template_points');
    }
};
