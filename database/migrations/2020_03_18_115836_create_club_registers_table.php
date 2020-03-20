<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester');
            $table->unsignedInteger('club_id');//目錄id
            $table->unsignedInteger('club_student_id');//目錄id
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
        Schema::dropIfExists('club_registers');
    }
}
