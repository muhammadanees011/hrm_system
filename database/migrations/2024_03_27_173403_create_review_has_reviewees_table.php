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
        Schema::create('review_has_reviewees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('review_id');
            $table->integer('user_id');
            $table->integer('buddy_reviewers')->default(0);
            $table->integer('management_reviewers')->default(0);      
            $table->integer('buddy_reviews')->default(0);
            $table->integer('management_reviews')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_has_reviewees');
    }
};
