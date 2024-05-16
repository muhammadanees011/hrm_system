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
        Schema::create('compensation_review_has_reviewees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compensation_review_id');
            $table->foreign('compensation_review_id')->references('id')->on('compensation_reviews')->onDelete('cascade');
            $table->unsignedBigInteger('reviewee_id');
            $table->foreign('reviewee_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('base_salary')->nullable();
            $table->integer('recomended_increase_percentage')->nullable();
            $table->integer('recomended_increase_amount')->nullable();
            $table->integer('new_base_salary')->nullable();
            $table->string('who_can_reviewe')->nullable();
            $table->enum('eligible', ['yes', 'no']);
            $table->enum('status', ['Approved', 'Pending', 'Rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensation_review_has_reviewees');
    }
};
