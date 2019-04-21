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
            $table->unsignedInteger('class_dis');//課務安排
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('status');//1送審中 2已完成  3退回！
            $table->unsignedInteger('deputy_user_id');
            $table->date('deputy_date')->nullable();
            $table->unsignedInteger('check1_user_id')->nullable();
            $table->date('check1_date')->nullable();
            $table->unsignedInteger('check2_user_id')->nullable();
            $table->date('check2_date')->nullable();
            $table->unsignedInteger('check3_user_id')->nullable();
            $table->date('check3_date')->nullable();
            $table->unsignedInteger('check4_user_id')->nullable();
            $table->date('check4_date')->nullable();
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
