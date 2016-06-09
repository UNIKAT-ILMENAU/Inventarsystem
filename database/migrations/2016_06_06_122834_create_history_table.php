<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('CommentID')->unsigned()->nullable();
            $table->foreign('CommentID')->references('id')->on('comment')->onDelete('cascade');
            $table->integer('CreatedByID')->unsigned();
            $table->foreign('CreatedByID')->references('id')->on('user');
            $table->integer('Item_ID')->unsigned();
            $table->foreign('Item_ID')->references('id')->on('item');
            $table->integer('Event_ID')->unsigned();
            $table->foreign('Event_ID')->references('id')->on('event');
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
        Schema::drop('history');
    }
}
