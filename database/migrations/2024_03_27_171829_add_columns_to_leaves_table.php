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
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('leave_duration')->nullable()->after('leave_type_id');
            $table->integer('duration_hours')->nullable()->after('leave_duration');
            $table->string('start_time')->nullable()->after('end_date');
            $table->string('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('leave_duration');
            $table->dropColumn('duration_hours');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
};
