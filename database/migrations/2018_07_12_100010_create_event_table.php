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
        Schema::defaultStringLength(191);
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')
                  ->on('location')->onDelete('cascade');
            $table->string('name');
            $table->integer('age_limit')->default('0');
            $table->date('date_start');
            $table->date('date_finish');
            $table->timestamps();

            $table->index('id');
            $table->index('location_id');
            $table->index('date_start');
            $table->index('date_finish');
            $table->index('age_limit');

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
