<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no');
            $table->unsignedInteger('semester');
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('telephone_num')->nullable();
            $table->string('money');
            $table->string('people')->nullable();
            $table->string('teacher_info')->nullable();
            $table->string('start_date')->nullable();
            $table->string('start_time');
            $table->string('place')->nullable();
            $table->string('ps')->nullable();
            $table->unsignedInteger('taking');//正取
            $table->unsignedInteger('prepare');//備取
            $table->string('year_limit')->nullable();//學生年齡限制
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
        Schema::dropIfExists('clubs');
    }
}
