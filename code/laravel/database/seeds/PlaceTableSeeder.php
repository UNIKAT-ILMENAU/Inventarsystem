<?php

use Illuminate\Database\Seeder;

class PlaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $placeA = new \App\Place;
        $placeA->name = "Building A";
        $placeA->save();

        $placeB = new \App\Place;
        $placeB->name = "Building B";
        $placeB->save();

        $placeA1 = new \App\Place;
        $placeA1->name = "Room A1";
        $placeA1->parent = $placeA->id;
        $placeA1->save();

        $placeA2 = new \App\Place;
        $placeA2->name = "Room A2";
        $placeA2->parent = $placeA->id;
        $placeA2->save();

        $placeA11 = new \App\Place;
        $placeA11->name = "Room A11";
        $placeA11->parent = $placeA1->id;
        $placeA11->save();

    }
}
