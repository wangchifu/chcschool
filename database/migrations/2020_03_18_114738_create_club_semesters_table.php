<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_semesters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester')->unique();
            $table->string('start_date');
            $table->string('stop_date');
            $table->tinyInteger('club_limit');//可報社團限制
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
        Schema::dropIfExists('club_semesters');
    }
}
