<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixes', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type');//1是資訊設備,2是總務設備
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->text('content');
            $table->text('reply')->nullable();;
            $table->tinyInteger('situation');//1是處理ok,2是處理中,3申報中
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
        Schema::dropIfExists('fixes');
    }
}
