<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLunchFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lunch_factories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('teacher_money');
            $table->tinyInteger('disable')->nullable();//停用
            $table->string('fid');//帳號
            $table->string('fpwd');//密碼
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
        Schema::dropIfExists('lunch_factories');
    }
}
