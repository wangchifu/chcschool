<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchTeaDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_tea_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_date');
            $table->string('enable');//eat訂餐；no_eat沒有訂或退餐
            $table->unsignedInteger('semester');
            $table->unsignedInteger('lunch_order_id');
            $table->unsignedInteger('user_id');
            $table->string('lunch_place_id');
            $table->string('lunch_factory_id');
            $table->unsignedInteger('eat_style');//1葷食 2素食
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
        Schema::dropIfExists('lunch_tea_dates');
    }
}
