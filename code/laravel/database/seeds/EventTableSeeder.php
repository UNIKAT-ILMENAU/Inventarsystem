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
					'Name'=> 'CreateItem',
					'Description'=> 'Create a new Item',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'DeactivateItem',
					'Description'=> 'Deactivate an item',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'ChangeItem',
					'Description'=> 'Change Item attributes',
					'EventValue'=> 'User_ID',
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'CreateRental',
					'Description'=> 'Create a Rental',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'UpdateRental',
					'Description'=> 'Update a Rental',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'UseMaterial',
					'Description'=> 'Use a Meterial',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'RefillMaterial',
					'Description'=> 'Refill a Material',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'DeviceDefect',
					'Description'=> 'Marks a device as defective',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'ItemLost',
					'Description'=> 'Marks an Item as lost ',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'SellMaterial',
					'Description'=> 'Sell a Material ',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					]
				  ];

		DB::table('event')->insert($event);
    }
}
