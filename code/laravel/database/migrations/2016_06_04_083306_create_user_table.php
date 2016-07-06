<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('member_id')->unsigned()->nullable();
            $table->foreign('member_id')->references('id')->on('member');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('Street')->nullable();
            $table->string('City')->nullable();
            $table->string('ZIP')->nullable();
            $table->string('MobilePhone')->nullable();
            $table->string('Email')->unique();
            $table->string('Matrikel')->nullable();
            $table->string('RegistrationToken')->nullable();
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
        Schema::drop('user');
    }
}
