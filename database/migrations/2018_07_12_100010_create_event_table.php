<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')
                  ->on('location')->onDelete('cascade');
            $table->integer('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')
                  ->on('schedule')->onDelete('cascade');
            $table->integer('ticket_type_id')->unsigned();
            $table->foreign('ticket_type_id')->references('id')
                  ->on('ticket_type')->onDelete('cascade');
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
        Schema::dropIfExists('event');
    }
}
