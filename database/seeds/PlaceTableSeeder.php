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
		DB:: table('place')->delete();

		$place = [
					[
					'Name'=> 'House M',
					'CreatedByID'=> 4,
					'BeforeID'=> NULL
					],
					[
					'Name'=> 'House L',
					'CreatedByID'=> 3,
					'BeforeID'=> NULL
					],
					[
					'Name'=> 'Room 201',
					'CreatedByID'=> 5,
					'BeforeID'=> 1
					],
					[
					'Name'=> 'Room 203',
					'CreatedByID'=> 1,
					'BeforeID'=> 2
					],
					[
					'Name'=> 'Locker 5',
					'CreatedByID'=> 2,
					'BeforeID'=> 4
					],
					
				  ];

		DB::table('place')->insert($place);
    }
}
