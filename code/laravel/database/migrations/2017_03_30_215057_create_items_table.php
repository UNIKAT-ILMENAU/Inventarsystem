<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->enum('type', ['DEVICE', 'MATERIAL']);
            $table->integer('state')->nullable();
            $table->integer('place_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('description')->nullable();
            $table->boolean('deleted')->default(0);
            $table->boolean('visible');

            // Data for material
            $table->double('storage_value', 15, 2)->nullable();
            $table->double('critical_storage_value', 15, 2)->nullable();
            $table->string('uom')->nullable();
            $table->string('uom_short')->nullable();
            $table->string('build_type')->nullable();
            $table->double('sale_price', 15, 2)->nullable();

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
        Schema::dropIfExists('items');
    }
}
