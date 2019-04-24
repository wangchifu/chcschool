<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAbsentOutlays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_absent_outlays', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_absent_id');//假單
            $table->dateTime('outlay_date');//差旅費日期
            $table->string('places');//起迄地
            $table->string('remember');//工作記要
            $table->unsignedInteger('outlay1');//價1
            $table->unsignedInteger('outlay2');//價1
            $table->unsignedInteger('outlay3');//價1
            $table->unsignedInteger('outlay4');//價1
            $table->unsignedInteger('outlay5');//價1
            $table->unsignedInteger('outlay6');//價1
            $table->unsignedInteger('outlay7');//價1
            $table->unsignedInteger('outlay8');//價1
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
        Schema::dropIfExists('teacher_absent_outlays');
    }
}
