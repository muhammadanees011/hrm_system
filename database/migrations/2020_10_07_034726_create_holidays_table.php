<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'holidays', function (Blueprint $table){
            $table->id();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('occasion');
            $table->integer('total_days');
            $table->integer('user_id');
            $table->integer('isNextYear')->nullable();
            $table->enum('status', ['Approved', 'Pending', 'Rejected'])->default('Pending');
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
        Schema::dropIfExists('holidays');
    }
}
