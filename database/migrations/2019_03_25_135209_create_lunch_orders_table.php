<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->unsignedInteger('semester');
            $table->string('rece_name');//收據抬頭
            $table->string('rece_date');//收據開立日期
            $table->string('rece_no');//收據字號
            $table->unsignedInteger('rece_num');//收據起始號
            $table->string('order_ps')->nullable();//說明
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
        Schema::dropIfExists('lunch_orders');
    }
}
