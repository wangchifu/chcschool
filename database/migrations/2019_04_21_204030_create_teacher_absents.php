<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAbsents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_absents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester');//學年度
            $table->float('day')->nullable();//請假幾日
            $table->float('hour')->nullable();//請假幾個小時
            $table->unsignedInteger('user_id');//請假人
            $table->string('reason');//事由
            $table->unsignedInteger('abs_kind');//請假類別
            $table->string('place')->nullable();//出差地點
            $table->unsignedInteger('class_dis');//課務安排
            $table->string('class_file')->nullable();//課務銜接單
            $table->tinyInteger('month')->nullable();
            $table->string('start_date');
            $table->string('end_date');
            $table->unsignedInteger('status');//1送審中 2已完成  3退回！
            $table->unsignedInteger('deputy_user_id');
            $table->dateTime('deputy_date')->nullable();
            $table->unsignedInteger('check1_user_id')->nullable();
            $table->dateTime('check1_date')->nullable();
            $table->unsignedInteger('check2_user_id')->nullable();
            $table->dateTime('check2_date')->nullable();
            $table->unsignedInteger('check3_user_id')->nullable();
            $table->dateTime('check3_date')->nullable();
            $table->unsignedInteger('check4_user_id')->nullable();
            $table->dateTime('check4_date')->nullable();
            $table->string('note')->nullable();//證明說明
            $table->string('note_file')->nullable();//證明檔案
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
        Schema::dropIfExists('teacher_absents');
    }
}
