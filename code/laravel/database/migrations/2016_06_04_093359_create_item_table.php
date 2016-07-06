<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->increments('Id')->unsigned();
            $table->integer('material_id')->unsigned()->nullable();
            $table->foreign('material_id')->references('id')->on('material');
            $table->string('Name');
            $table->integer('State')->nullable();
            $table->integer('CreatedByID')->unsigned();
            $table->foreign('CreatedByID')->references('id')->on('user');
            $table->integer('PlaceStartID')->unsigned();
            $table->foreign('PlaceStartID')->references('id')->on('place')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('CategoryStartID')->unsigned();
            $table->foreign('CategoryStartID')->references('id')->on('category')->onUpdate('cascade')->onDelete('cascade');
            $table->string('Description')->nullable();
            $table->integer('Attachment_ID')->nullable()->unsigned();
            $table->foreign('Attachment_ID')->references('id')->on('attachment')->onUpdate('cascade');
            $table->boolean('Deleted');
            $table->boolean('Visible');
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
        Schema::drop('item');
    }
}
