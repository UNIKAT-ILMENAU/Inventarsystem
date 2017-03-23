<?php

use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB:: table('event')->delete();

		$event = [
					[
					    'id' => 1,
					'Name'=> 'CreateItem',
					'Description'=> 'Create a new Item',
					'EventValue'=> NULL
					],
					[
					    'id' => 2,
					'Name'=> 'DeactivateItem',
					'Description'=> 'Deactivate an item',
					'EventValue'=> NULL
					],
					[
                        'id' => 3,
					'Name'=> 'ChangeItem',
					'Description'=> 'Change Item attributes',
					'EventValue'=> 'User_ID'
					],
					[
                        'id' => 4,
					'Name'=> 'ItemRented',
					'Description'=> 'Create a Rental',
					'EventValue'=> NULL
					],
					[
                        'id' => 5,
					'Name'=> 'ItemBroughtBack',
					'Description'=> 'Update a Rental',
					'EventValue'=> NULL
					],
					[
                        'id' => 6,
					'Name'=> 'UseMaterial',
					'Description'=> 'Use a Meterial',
					'EventValue'=> NULL
					],
					[
                        'id' => 7,
					'Name'=> 'RefillMaterial',
					'Description'=> 'Refill a Material',
					'EventValue'=> NULL
					],
					[
                        'id' => 8,
					'Name'=> 'DeviceDefect',
					'Description'=> 'Marks a device as defective',
					'EventValue'=> NULL
					],
					[
                        'id' => 9,
					'Name'=> 'ItemLost',
					'Description'=> 'Marks an Item as lost ',
					'EventValue'=> NULL
					],
					[
                        'id' => 10,
					'Name'=> 'SellMaterial',
					'Description'=> 'Sell a Material ',
					'EventValue'=> NULL
					]
				  ];

		DB::table('event')->insert($event);
    }
}
