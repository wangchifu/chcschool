<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester')->unique();
            $table->string('eat_styles');//1葷食合菜 2素食合菜 3葷食便當 4素食便當
            $table->tinyInteger('die_line');
            $table->tinyInteger('teacher_open')->nullable();//教師隨時可訂餐
            $table->tinyInteger('disable')->nullable();//停止退餐訂餐
            $table->string('all_rece_name');//收據抬頭
            $table->string('all_rece_date');//收據開立日期
            $table->string('all_rece_no');//收據字號
            $table->unsignedInteger('all_rece_num');//收據起始號
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
        Schema::dropIfExists('lunch_setups');
    }
}
