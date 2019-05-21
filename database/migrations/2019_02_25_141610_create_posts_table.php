<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('title_image')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('job_title');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('views');
            $table->tinyInteger('insite')->nullable();//校內？1內部文件 2榮榜
            $table->tinyInteger('top')->nullable();//置頂
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
        Schema::dropIfExists('posts');
    }
}
