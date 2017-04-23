<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryA = new \App\Category;
        $categoryA->name = 'CategroyA';
        $categoryA->save();

        $categoryB = new \App\Category;
        $categoryB->name = 'CategroyB';
        $categoryB->description = 'Categroy B description text';
        $categoryB->save();

        $categoryB1 = new \App\Category;
        $categoryB1->name = 'CategroyB1';
        $categoryB1->description = 'Categroy B1 description text';
        $categoryB1->parent = $categoryB->id;
        $categoryB1->save();

        $categoryB2 = new \App\Category;
        $categoryB2->name = 'CategroyB2';
        $categoryB2->parent = $categoryB->id;
        $categoryB2->save();

        $categoryB11 = new \App\Category;
        $categoryB11->name = 'CategroyB11';
        $categoryB11->description = 'Categroy B11 description text';
        $categoryB11->parent = $categoryB1->id;
        $categoryB11->save();

    }
}
