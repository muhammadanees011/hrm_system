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
        Schema::create('directory_has_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('directory_id');
            $table->foreign('directory_id')->references('id')->on('document_directories')->onDelete('cascade');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directory_has_documents');
    }
};
