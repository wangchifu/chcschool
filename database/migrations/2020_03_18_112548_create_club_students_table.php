<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester');
            $table->string('no');
            $table->string('name');
            $table->string('class_num');
            $table->string('pwd');
            $table->string('birthday');
            $table->string('parents_telephone')->nullable();
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
        Schema::dropIfExists('club_students');
    }
}
