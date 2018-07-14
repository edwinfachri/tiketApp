<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')
                  ->on('customer')->onDelete('cascade');
            $table->integer('ticket_id')->unsigned();
            $table->foreign('ticket_id')->references('id')
                  ->on('ticket')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('uid');
            $table->timestamps();

            $table->index(['id', 'customer_id', 'ticket_id', 'uid']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
