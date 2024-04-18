<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_trackings', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('employee_id');
            $table->integer('performancecycle_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->dateTime('checkin_time')->nullable();
            $table->integer('progress')->default(0);
            $table->enum('visibility',['Private','Shared']);
            $table->enum('status',['Pending','Done','On Track','Off Track'])->default('Pending');
            $table->integer('created_by');
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goal_trackings');
    }
}
