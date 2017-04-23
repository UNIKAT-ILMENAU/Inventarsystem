<?php

use Illuminate\Database\Seeder;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dev1 = new \App\Item;
        $dev1->name = 'Device 1';
        $dev1->type = 'DEVICE';
        $dev1->state = 1;
        $dev1->place_id = 1;
        $dev1->category_id = 1;
        $dev1->description = 'Device 1 description text';
        $dev1->visible = 1;
        $dev1->save();

        $dev2 = new \App\Item;
        $dev2->name = 'Device 2';
        $dev2->type = 'DEVICE';
        $dev2->state = 1;
        $dev2->place_id = 1;
        $dev2->category_id = 1;
        $dev2->description = 'Device 2 description text';
        $dev2->visible = 0;
        $dev2->save();

        $dev3 = new \App\Item;
        $dev3->name = 'Device 3';
        $dev3->type = 'DEVICE';
        $dev3->state = 2;
        $dev3->place_id = 1;
        $dev3->category_id = 1;
        $dev3->description = 'Device 3 description text';
        $dev3->visible = 1;
        $dev3->save();

        $material1 = new \App\Item;
        $material1->name = 'Material 1';
        $material1->type = 'MATERIAL';
        $material1->state = 1;
        $material1->place_id = 1;
        $material1->category_id = 1;
        $material1->description = 'MATERIAL 1 description text';
        $material1->visible = 1;
        $material1->storage_value = 10;
        $material1->critical_storage_value = 5;
        $material1->uom = 'Unit';
        $material1->uom_short = 'u';
        $material1->build_type = 'bt';
        $material1->sale_price = 4;
        $material1->save();
    }
}
