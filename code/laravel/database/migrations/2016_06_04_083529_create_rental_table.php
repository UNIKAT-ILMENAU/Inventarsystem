<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('User_id')->unsigned()->onUpdate('cascade');
            $table->foreign('User_id')->references('id')->on('user');
            $table->integer('CreatedByID')->unsigned();
            $table->foreign('CreatedByID')->references('id')->on('user');
            $table->string('State');
            $table->timestamp('EndDate');
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
        Schema::drop('rental');
    }
}
