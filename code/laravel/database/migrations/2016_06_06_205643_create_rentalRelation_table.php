<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentalRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentalrelation', function (Blueprint $table) {

            $table->integer('ItemID')->unsigned();
            $table->foreign('ItemID')->references('id')->on('item');
            $table->integer('Amount')->nullable();
            $table->integer('Amount_After')->nullable();
            $table->integer('RentalID')->unsigned();
            $table->foreign('RentalID')->references('id')->on('rental');
            $table->integer('State')->unsigned();
            $table->timestamp('BroughtBack');
             });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('rentalrelation');
    }
}
