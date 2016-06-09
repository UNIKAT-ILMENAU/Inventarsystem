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
					'Name'=> 'Create_item',
					'Description'=> 'Create a new Item',
					'EventValue'=> NULL,
					'CreatedByID'=> 5
					],
					[
					'Name'=> 'Delete_item',
					'Description'=> 'Delete an existing item',
					'EventValue'=> NULL,
					'CreatedByID'=> 2
					],
					[
					'Name'=> 'Create_rental',
					'Description'=> 'Create a new rental',
					'EventValue'=> 'User_ID',
					'CreatedByID'=> 4
					],
					[
					'Name'=> 'BringBackItem',
					'Description'=> 'Delete an single item from a rental',
					'EventValue'=> NULL,
					'CreatedByID'=> 1
					],
					[
					'Name'=> 'update_item',
					'Description'=> 'Update an Item',
					'EventValue'=> NULL,
					'CreatedByID'=> 3
					],
					
				  ];

		DB::table('event')->insert($event);
    }
}
