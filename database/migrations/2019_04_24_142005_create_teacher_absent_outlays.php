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
            $table->date('outlay_date');//差旅費日期
            $table->string('places');//起迄地
            $table->string('remember');//工作記要
            $table->unsignedInteger('outlay1')->nullable();//價1
            $table->unsignedInteger('outlay2')->nullable();//價1
            $table->unsignedInteger('outlay3')->nullable();//價1
            $table->unsignedInteger('outlay4')->nullable();//價1
            $table->unsignedInteger('outlay5')->nullable();//價1
            $table->unsignedInteger('outlay6')->nullable();//價1
            $table->unsignedInteger('outlay7')->nullable();//價1
            $table->unsignedInteger('outlay8')->nullable();//價1
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
