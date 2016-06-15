<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('Name');
            $table->string('Description')->nullable();
            $table->integer('BeforeID')->unsigned()->nullable();
            $table->timestamps();
        });

        $dbh = DB::getPdo(); 
        $dbh->query("ALTER TABLE category ADD CONSTRAINT fk_category_BeforeID FOREIGN KEY (BeforeID) REFERENCES category (id)");  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category');
    }
}
