<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table){
            $table->bigIncrements('id');
            // $table->integer('branch_id');
            // $table->longText('department_id');
            $table->unsignedBigInteger('organizer_id');
            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('invitee_id');
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('meeting_template_id');
            $table->foreign('meeting_template_id')->references('id')->on('meeting_templates')->onDelete('cascade');
            $table->string('title');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('note')->nullable();
            $table->text('organizer_note')->nullable();
            $table->text('invitee_note')->nullable();
            $table->enum('status',['Scheduled','In Progress','Completed'])->default('Scheduled');
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
        Schema::dropIfExists('meetings');
    }
}
