<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'pay_slips', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('employee_id');
            $table->float('net_payble', 15,2);
            $table->string('salary_month');
            $table->integer('status');
            $table->float('basic_salary', 15,2);
            $table->text('allowance');
            $table->text('commission');
            $table->text('loan');
            $table->text('saturation_deduction');
            $table->text('other_payment');
            $table->text('overtime');
            $table->text('incometax');
            $table->text('bonus');
            $table->text('providentfunds');
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
        Schema::dropIfExists('pay_slips');
    }
}
