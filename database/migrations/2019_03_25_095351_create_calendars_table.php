<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('calendar_week_id');
            $table->unsignedInteger('semester');
            $table->tinyInteger('calendar_kind');//0學校行政；1教務；2學務；3總務；4輔導
            $table->string('content');
            $table->unsignedInteger('user_id');
            $table->string('job_title');
            $table->unsignedInteger('order_by')->nullable();
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
        Schema::dropIfExists('calendars');
    }
}
