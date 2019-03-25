<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchOrderDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_order_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_date')->unique();
            $table->tinyInteger('enable');//1供餐；0沒有供餐
            $table->unsignedInteger('semester');
            $table->unsignedInteger('lunch_order_id');
            $table->string('date_ps')->nullable();//該日說明
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
        Schema::dropIfExists('lunch_order_dates');
    }
}
