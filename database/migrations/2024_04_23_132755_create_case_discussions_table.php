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
        Schema::create('case_discussions', function (Blueprint $table) {
            $table->id();
            $table->string('case_code');
            $table->integer('sender');
            $table->integer('receiver');
            $table->text('text')->nullable();
            $table->string('type');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_discussions');
    }
};
