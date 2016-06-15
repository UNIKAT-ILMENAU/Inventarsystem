<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('place', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('Name');
            $table->integer('BeforeID')->unsigned()->nullable();
           
            $table->integer('CreatedByID')->unsigned();
            $table->foreign('CreatedByID')->references('id')->on('user');
            $table->timestamps();

        });
         
         $dbh = DB::getPdo(); 
         $dbh->query("ALTER TABLE place ADD CONSTRAINT fk_place_BeforeID FOREIGN KEY (BeforeID) REFERENCES place (id)");  
    }

    /**
     * Reverse the migrations.I
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('place');
    }
}
