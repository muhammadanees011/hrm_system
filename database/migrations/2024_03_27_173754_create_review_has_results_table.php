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
        Schema::create('review_has_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('review_id');
            $table->integer('reviewer_id');
            $table->integer('reviewee_id');
            $table->integer('question_id');
            $table->string('selected_option');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_has_results');
    }
};
