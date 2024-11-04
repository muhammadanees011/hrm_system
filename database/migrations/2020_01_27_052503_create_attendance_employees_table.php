<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id');
            $table->date('date');
            $table->string('status');
            $table->time('clock_in');
            $table->time('clock_out');
            $table->time('late');
            $table->time('break_start')->default('00:00:00');
            $table->time('break_end')->default('00:00:00');
            $table->time('total_break')->default('00:00:00');
            $table->time('early_leaving');
            $table->time('overtime');
            $table->time('total_rest');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_employees');
    }
}
