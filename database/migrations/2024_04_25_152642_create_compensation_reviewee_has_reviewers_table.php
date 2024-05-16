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
        Schema::create('compensation_reviewee_has_reviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compensation_review_id');
            $table->foreign('compensation_review_id','fk_comp_review_reviewer_comp_review_id')->references('id')->on('compensation_reviews')->onDelete('cascade');
            $table->unsignedBigInteger('reviewee_id');
            $table->foreign('reviewee_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('reviewer_id');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensation_reviewee_has_reviewers');
    }
};
