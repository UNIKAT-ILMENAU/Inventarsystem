<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->double('StorageValue', 15, 2);
            $table->double('CriticalStorageValue', 15, 2)->nullable();
            $table->string('UoM')->nullable();
            $table->string('UoM_short')->nullable();
            $table->string('BuildType')->nullable();
            $table->double('SalePrice', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('material');
    }
}
