<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique;
            $table->string('name');
            $table->unsignedInteger('order_by')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('admin')->nullable();
            $table->string('code')->nullable();
            $table->string('school')->nullable();
            $table->string('kind')->nullable();
            $table->string('title')->nullable();
            $table->string('login_type')->nullable();//null為本機登入,gsuite為gsuite登入
            $table->tinyInteger('disable')->nullable();//1為停用,null為啟用
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
